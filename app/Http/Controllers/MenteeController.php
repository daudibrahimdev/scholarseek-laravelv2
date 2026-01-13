<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LearningSessionParticipant;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPackage;
use App\Models\LearningSession;
use App\Models\Package;
use Carbon\Carbon;
use App\Models\Transaction;

use App\Models\Mentor;



class MenteeController extends Controller
{
    public function index()
    {
        // Ambil 4 Mentor Terbaik (Contoh: berdasarkan rating tertinggi atau jumlah sesi terbanyak)
        // Untuk saat ini, kita ambil 4 Mentor yang sudah verified saja.
        $featuredMentors = Mentor::with('user')
                                ->where('verification_status', 'verified')
                                ->latest()
                                ->take(4)
                                ->get();

        return view('mentee.dashboard.index', compact('featuredMentors'));
    }

    public function packages()
    {
        // Ambil semua paket, urutkan dari yang termurah
        $packages = Package::orderBy('price', 'asc')->get();

        return view('mentee.packages.index', compact('packages'));
    }

    /**
     * Tampilkan halaman pilih mentor untuk paket tertentu.
     */
    public function showMentorSelection($userPackageId)
    {
        $userPackage = UserPackage::with('package')
            ->where('id', $userPackageId)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending_assignment', 'rejected'])
            ->firstOrFail();

        // Ambil data untuk filter
        $categories = \App\Models\ScholarshipCategory::all();
        
        $query = Mentor::with('user')
            ->where('verification_status', 'verified')
            ->where('is_available', true);

        // Search & Filter (Tetap pertahankan logic lama lo)
        if ($search = request('search')) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        $mentors = $query->paginate(8);

        return view('mentee.mentor.assign', compact('userPackage', 'mentors', 'categories'));
    }
    public function indexMentors(Request $request)
{
    // Query Dasar: Ambil mentor beserta data user (untuk dapet Nama Mentor)
    $query = Mentor::with('user'); 

    // 1. Filter Pencarian (Nama User & Bio Mentor)
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            // Cari di tabel 'users' melalui relasi 'user'
            $q->whereHas('user', function($u) use ($search) {
                $u->where('name', 'like', "%{$search}%");
            })
            // Cari di kolom 'bio' di tabel 'mentors'
            ->orWhere('bio', 'like', "%{$search}%");
        });
    }

    // 2. Filter Domisili (Kolom domicile_city)
    if ($request->has('city')) {
        $query->whereIn('domicile_city', $request->city);
    }

    // 3. Filter Keahlian (Kolom JSON expertise_areas)
    if ($request->has('expertise')) {
        $expertises = $request->expertise;
        $query->where(function($q) use ($expertises) {
            foreach ($expertises as $exp) {
                // Sesuai dump SQL lo, expertise_areas itu JSON
                $q->orWhereJsonContains('expertise_areas', $exp);
            }
        });
    }

    $mentors = $query->paginate(9);

    // Ambil data pendukung buat Sidebar Filter
    $cities = Mentor::select('domicile_city')
        ->distinct()
        ->whereNotNull('domicile_city')
        ->pluck('domicile_city');
        
    $scholarshipCategories = \App\Models\ScholarshipCategory::all();

    return view('mentee.mentors.index', compact('mentors', 'cities', 'scholarshipCategories'));
}

    /**
     * Simpan pilihan mentor mentee.
     */
    public function assignMentor(Request $request)
{
    $request->validate([
        'user_package_id' => 'required|exists:user_packages,id',
        'mode' => 'required|in:manual,auto',
        // Jika auto (matchmaking), field target wajib ada
        'target_degree' => 'nullable|string',
        'target_country' => 'nullable|string', 
        'target_scholarship' => 'nullable|string',
        'mentor_id' => 'required_if:mode,manual|exists:mentors,id',
    ]);

    $userPackage = UserPackage::where('id', $request->user_package_id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    if ($request->mode === 'auto') {
        // Alur Matchmaking: Mentor_id dikosongkan, status jadi open_request
        $userPackage->update([
            'mentor_id' => null,
            'status' => 'open_request',
            'target_degree' => $request->target_degree,
            'target_country' => $request->target_country,
            'target_scholarship' => $request->target_scholarship,
            'request_note' => $request->request_note,
            'requested_at' => now(),
        ]);
        $msg = 'Permintaan Matchmaking berhasil! Profilmu sekarang ada di Job Board Mentor.';
    } else {
        // Alur Manual: Mentor_id diisi, status jadi pending_approval
        $userPackage->update([
            'mentor_id' => $request->mentor_id,
            'status' => 'pending_approval',
            'requested_at' => now(),
        ]);
        $msg = 'Permintaan bimbingan telah dikirim ke mentor pilihanmu.';
    }

    return redirect()->route('mentee.consultations.index')->with('success', $msg);
}

    public function consultationsIndex()
{
    $menteeId = Auth::id();

    // 1. Cek Paket yang statusnya 'pending_assignment' (baru bayar tapi belum pilih mentor/matchmaking)
    $pendingAssignmentPackage = UserPackage::where('user_id', $menteeId)
                                        ->where('status', 'pending_assignment')
                                        ->first();
    
    // Jika ada paket yang terkatung-katung belum di-assign sama sekali, paksa mentee ke halaman pemilihan
    if ($pendingAssignmentPackage) {
        return redirect()->route('mentee.mentor.assign.form', [
            'user_package_id' => $pendingAssignmentPackage->id
        ]);
    }
    
    // 2. AMBIL SEMUA PAKET (Bukan cuma active) agar muncul di tabel riwayat
    // Kita ambil status: active, pending_approval, open_request, used_up, rejected
    $activePackages = UserPackage::with(['package', 'mentor.user']) 
                                ->where('user_id', $menteeId)
                                ->whereIn('status', ['active', 'pending_approval', 'open_request', 'used_up', 'rejected'])
                                ->orderBy('updated_at', 'desc')
                                ->get();
    
    // Ambil sesi mendatang hanya dari paket yang statusnya sudah 'active'
    $assignedMentorIds = $activePackages->where('status', 'active')->pluck('mentor_id')->filter()->unique()->toArray();
    $upcomingSessions = [];
    if (!empty($assignedMentorIds)) {
        $upcomingSessions = LearningSession::with('mentor.user', 'participants')
                                            ->whereIn('mentor_id', $assignedMentorIds)
                                            ->where('start_time', '>', now())
                                            ->get();
    }
    
    return view('mentee.consultations.index', compact('activePackages', 'upcomingSessions'));
    }

    public function cancelMatchmaking($id)
    {
        $package = UserPackage::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Sesuai dump SQL lo, status yang bisa dibatalkan adalah pending_approval dan open_request
        if (!in_array($package->status, ['pending_approval', 'open_request', 'rejected'])) {
            return back()->with('error', 'Status paket saat ini tidak dapat dibatalkan.');
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Reset paket ke status awal setelah bayar agar mentee bisa pilih mentor lagi
            $package->update([
                'status' => 'pending_assignment', 
                'mentor_id' => null, 
                'requested_at' => null,
                'request_note' => null
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('mentee.consultations.index')
                ->with('success', 'Permintaan mentor berhasil dibatalkan. Silakan pilih mentor baru.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal membatalkan permintaan: ' . $e->getMessage());
        }
    }

    public function upcomingSessions()
    {
        $menteeId = Auth::id();

        // 1. Jalankan sinkronisasi status instan (Backup jika cronjob delay)
        // Gunakan helper yang sudah kita buat tadi jika perlu, 
        // atau biarkan command di background yang menangani.

        // 2. Ambil sesi yang statusnya masih aktif (Scheduled & Ongoing)
        $sessions = LearningSessionParticipant::with(['session.mentor.user'])
            ->where('mentee_id', $menteeId)
            ->whereHas('session', function($query) {
                // Kita filter berdasarkan status, bukan cuma jam mulai
                $query->whereIn('status', ['scheduled', 'ongoing'])
                    ->orderBy('start_time', 'asc');
            })
            ->get();

        return view('mentee.sessions.upcoming', compact('sessions'));
    }

    // HISTORY
    /**
     * Menampilkan riwayat sesi yang sudah selesai
     */
    public function history()
{
    // FIX: Langsung ambil ID User yang login karena tidak ada tabel 'mentees'
    // mentee_id di database = user_id
    $menteeId = \Illuminate\Support\Facades\Auth::id();
    
    // Query ke tabel learning_sessions lewat learning_session_participants
    $history = \App\Models\LearningSession::whereHas('participants', function($q) use ($menteeId) {
            $q->where('mentee_id', $menteeId);
        })
        ->where('end_time', '<', now()) // Ambil yang sudah lewat
        // ->where('is_hidden_for_mentee', false) // Hapus baris ini kalau kolomnya belum lo bikin di DB
        ->orderBy('end_time', 'desc')
        ->get();

    return view('mentee.sessions.history', compact('history'));
}
    /**
     * Menyembunyikan riwayat sesi dari pandangan mentee
     */
    public function hide($id)
    {
        $session = LearningSession::findOrFail($id);
        
        // Update kolom is_hidden_for_mentee yang kita buat di migration tadi
        $session->update(['is_hidden_for_mentee' => true]);

        return back()->with('success', 'Riwayat sesi berhasil dihapus dari daftar.');
    }

    public function transactionHistory()
    {
        // Ambil semua transaksi milik mentee yang sedang login
        // Kita panggil relasi 'package' (bukan userPackage) sesuai Model Transaction kamu
        $transactions = Transaction::with(['package'])
            ->where('user_id', Auth::id())
            ->whereNotIn('status', ['cancelled']) 
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mentee.transactions.index', compact('transactions'));
    }

    public function showInvoice($id)
    {
        // Cari transaksi berdasarkan ID dan pastikan milik user yang login
        $transaction = Transaction::with(['package', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('mentee.transactions.invoice', compact('transaction'));
    }
}

