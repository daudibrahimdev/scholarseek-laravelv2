<?php

namespace App\Http\Controllers;

use App\Models\LearningSession;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\LearningSessionParticipant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\UserPackage;



class LearningSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Dapatkan ID Mentor dari User yang login
        $mentor = Mentor::where('user_id', Auth::id())->firstOrFail();
        $mentorId = Auth::id(); // Ambil user_id, karena user_packages pakai user_id mentor
        
        // 2. Ambil sesi milik mentor ini
        $sessions = LearningSession::where('mentor_id', $mentor->id)
                                ->orderBy('start_time', 'asc')
                                ->get();

        // 3. Ambil Permintaan Sesi Pending (tetap ada)
        $pendingRequests = LearningSessionParticipant::whereHas('session', function($query) use ($mentor) {
            $query->where('mentor_id', $mentor->id);
        })
        ->where('status', 'pending')
        ->with(['mentee', 'session'])
        ->latest()
        ->take(5)
        ->get();

        // 4. >>> START: LOGIKA BARU UNTUK DROPDOWN ASSIGN MENTEE <<<
        $assignedPackages = UserPackage::with(['mentee', 'package']) // Pastikan menggunakan 'mentee' dan 'package'
            ->where('mentor_id', $mentor->id) // Jika kolom mentor_id di UserPackage menyimpan ID dari tabel MENTOR
            ->where('status', 'active')
            ->where('remaining_quota', '>', 0)
            ->get();
        // >>> END: LOGIKA BARU <<<
        
        // 5. Return view dengan data
        return view('mentor.schedule.index', compact('sessions', 'pendingRequests', 'assignedPackages'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //anying
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // >>> PERIKSA VALIDASI INI <<<
        $validatedData = $request->validate([
            // 1. INPUT UNTUK ASSIGN MENTEE (Field baru dan KRUSIAL)
            // Kita hanya membuat wajib jika tipe sesi adalah 1on1
            'user_package_id' => 'nullable|exists:user_packages,id', 
            
            // 2. INPUT SESI STANDAR
            'title' => 'required|string|max:255',
            'type' => 'required|in:1on1,group', // Pastikan type divalidasi
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'url_meeting' => 'nullable|url',
            'description' => 'nullable|string',
        ]);
        
        // VALIDASI LOGIKA BISNIS KRUSIAL: Jika tipe 1on1, user_package_id wajib ada
        if ($validatedData['type'] === '1on1' && empty($validatedData['user_package_id'])) {
            // Jika 1on1, tapi user_package_id kosong, kita kembalikan error spesifik.
            return redirect()->back()->withErrors(['user_package_id' => 'Untuk sesi Private (1-on-1), Anda wajib memilih Mentee yang akan dijadwalkan.']);
        }


        // --- KODE TRANSAKSI PEMOTONGAN KUOTA HANYA JALAN UNTUK 1ON1 ---
        
        if ($validatedData['type'] === '1on1') {
            
            // 1. Ambil data yang dibutuhkan untuk transaksi
            $mentor = Mentor::where('user_id', Auth::id())->firstOrFail();
            $userPackageId = $validatedData['user_package_id'];
            $userPackage = UserPackage::where('id', $userPackageId)
                ->where('mentor_id', $mentor->id) // Security check: Pastikan paket memang milik mentor ini
                ->lockForUpdate()
                ->firstOrFail();
                
            // 2. Cek kuota
            if ($userPackage->remaining_quota <= 0) {
                return redirect()->back()->with('error', "Gagal membuat sesi 1-on-1: Kuota Mentee ({$userPackage->mentee->name}) sudah habis.");
            }
            
            // 3. Potong Kuota dan simpan data sesi
            try {
                DB::beginTransaction();
                
                // Simpan Sesi
                $session = LearningSession::create([
                    'mentor_id' => $mentor->id,
                    'title' => $validatedData['title'],
                    'type' => $validatedData['type'],
                    'start_time' => $validatedData['start_time'],
                    'end_time' => $validatedData['end_time'],
                    'url_meeting' => $validatedData['url_meeting'],
                    'description' => $validatedData['description'],
                    'status' => 'scheduled', // 1on1 langsung scheduled
                    'max_participants' => 1, // Sesi 1on1 max 1
                ]);
                
                // Potong Kuota
                $userPackage->remaining_quota -= 1;
                $userPackage->save();
                
                // Buat Record Partisipan
                LearningSessionParticipant::create([
                    'learning_session_id' => $session->id,
                    'mentee_id' => $userPackage->user_id, 
                    'user_package_id' => $userPackageId,
                    'status' => 'registered', 
                ]);

                DB::commit();
                return redirect()->route('mentor.sessions.index')->with('success', 'Sesi Private berhasil dijadwalkan! Kuota Mentee telah dipotong.');
                
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Transaksi gagal: ' . $e->getMessage());
            }

        } else {
            
            // --- KODE UNTUK GROUP SESSION (TIDAK MEMOTONG KUOTA) ---
            
            // Ambil ID Mentor (jika belum diambil)
            $mentor = Mentor::where('user_id', Auth::id())->firstOrFail();
            
            // Simpan Sesi Group tanpa transaksi kuota
            LearningSession::create([
                'mentor_id' => $mentor->id,
                'title' => $validatedData['title'],
                'type' => $validatedData['type'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'url_meeting' => $validatedData['url_meeting'],
                'description' => $validatedData['description'],
                'status' => 'scheduled', // Group session berstatus scheduled, menunggu enrollment
                'max_participants' => 10, // Max peserta umum
            ]);
            
            return redirect()->route('mentor.sessions.index')->with('success', 'Sesi Group (Umum) berhasil dibuat dan dijadwalkan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LearningSession $session)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LearningSession $session)
    {
        //
    }

    /**
     * Update sesi yang sudah ada.
     */
    public function update(Request $request, LearningSession $session)
    {
        // 1. Cek Kepemilikan (Security)
        $mentor = Mentor::where('user_id', Auth::id())->first();
        if ($session->mentor_id !== $mentor->id) {
            abort(403, 'Anda tidak berhak mengubah sesi ini.');
        }

        // 2. Cek Apakah Sudah Ada Peserta
        $hasParticipants = $session->participants()->count() > 0;

        // 3. Validasi Input
        $request->validate([
            'title' => 'required|string|max:255',
            'url_meeting' => 'nullable|url',
            'description' => 'nullable|string',
            // Waktu & Tipe cuma divalidasi kalau BELUM ada peserta
            'start_time' => $hasParticipants ? 'nullable' : 'required|date|after:now',
            'end_time' => $hasParticipants ? 'nullable' : 'required|date|after:start_time',
            'type' => $hasParticipants ? 'nullable' : 'required|in:group,1on1',
        ]);

        // 4. Update Data (Sesuai Kondisi)
        if ($hasParticipants) {
            // Jika ada peserta, HANYA boleh ubah info non-krusial
            $session->update([
                'title' => $request->title,
                'url_meeting' => $request->url_meeting,
                'description' => $request->description,
                // start_time, end_time, type TIDAK diupdate
            ]);
            $message = 'Sesi diperbarui. (Waktu & Tipe tidak diubah karena sudah ada peserta)';
        } else {
            // Jika belum ada peserta, BEBAS ubah semua
            $session->update([
                'title' => $request->title,
                'type' => $request->type,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'url_meeting' => $request->url_meeting,
                'description' => $request->description,
                'max_participants' => $request->type == '1on1' ? 1 : 10,
            ]);
            $message = 'Sesi berhasil diperbarui sepenuhnya.';
        }

        return redirect()->route('mentor.sessions.index')
                         ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningSession $session)
    {
        $mentor = Mentor::where('user_id', Auth::id())->first();
        
        // Cek Kepemilikan
        if ($session->mentor_id !== $mentor->id) {
            abort(403, 'Anda tidak berhak menghapus sesi ini.');
        }

        // Cek Peserta (Proteksi Fatal)
        if ($session->participants()->count() > 0) {
            return redirect()->back()
                             ->with('error', 'Gagal menghapus! Sesi ini sudah memiliki peserta. Hubungi Admin untuk pembatalan.');
        }

        $session->delete();

        return redirect()->route('mentor.sessions.index')
                         ->with('success', 'Sesi berhasil dihapus.');
    }
}
