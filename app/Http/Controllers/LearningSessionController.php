<?php

namespace App\Http\Controllers;

use App\Models\LearningSession;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\LearningSessionParticipant;



class LearningSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Dapatkan ID Mentor dari User yang login
    $mentor = Mentor::where('user_id', Auth::id())->firstOrFail();

    // 2. Ambil sesi milik mentor ini (Untuk Tabel Jadwal & Kalender)
    $sessions = LearningSession::where('mentor_id', $mentor->id)
                               ->orderBy('start_time', 'asc') // Urutkan dari jadwal terdekat
                               ->get();

    // 3. Ambil Permintaan Sesi Pending (Untuk Tabel Permintaan)
    // Kita ambil dari tabel participants yang statusnya 'pending'
    $pendingRequests = LearningSessionParticipant::whereHas('session', function($query) use ($mentor) {
                            $query->where('mentor_id', $mentor->id);
                        })
                        ->where('status', 'pending')
                        ->with(['mentee', 'session']) // Eager load mentee dan session info
                        ->latest()
                        ->take(5) // Ambil 5 terbaru
                        ->get();

    // 4. Return view dengan data
    return view('mentor.schedule.index', compact('sessions', 'pendingRequests'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mentor = Mentor::where('user_id', Auth::id())->first();

        // Validasi
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:group,1on1',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'url_meeting' => 'nullable|url',
            'description' => 'nullable|string',
            // Harga dihapus dari validasi
        ]);

        // Simpan
        LearningSession::create([
            'mentor_id' => $mentor->id,
            'title' => $request->title,
            'type' => $request->type,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'url_meeting' => $request->url_meeting,
            'description' => $request->description,
            'price' => 0, // Default 0 (Harga diurus paket)
            'max_participants' => $request->type == '1on1' ? 1 : 10,
            'status' => 'scheduled',
        ]);

        return redirect()->route('mentor.sessions.index')
                         ->with('success', 'Sesi berhasil dijadwalkan!');
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
