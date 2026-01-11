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

    // 1. Cek Paket yang statusnya 'pending_assignment'
    $pendingAssignmentPackage = UserPackage::where('user_id', $menteeId)
                                        ->where('status', 'pending_assignment')
                                        ->first();
    
    // LOGIC REDIRECT: Jika ada paket yang belum di-assign, paksa redirect.
    if ($pendingAssignmentPackage) {
        return redirect()->route('mentee.mentor.assign.form', [
            'user_package_id' => $pendingAssignmentPackage->id
        ]);
    }
    
    // 2. Jika tidak ada pending, tampilkan daftar sesi aktif.
    // (Logic ini akan kita kembangkan di turn berikutnya, tapi sekarang kita kirim data aktif)
    $activePackages = UserPackage::with(['package', 'mentor.user']) 
                                ->where('user_id', $menteeId)
                                ->where('status', 'active')
                                ->get();
    
    // Ambil sesi mendatang dari mentor yang sudah di-assign
    $assignedMentorIds = $activePackages->pluck('mentor_id')->filter()->unique()->toArray();
    $upcomingSessions = [];
    if (!empty($assignedMentorIds)) {
        $upcomingSessions = LearningSession::with('mentor.user', 'participants')
                                            ->whereIn('mentor_id', $assignedMentorIds)
                                            ->where('start_time', '>', now())
                                            ->get();
    }
    
    // View ini akan menampilkan daftar paket aktif dan sesi yang akan datang
    return view('mentee.consultations.index', compact('activePackages', 'upcomingSessions'));
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

