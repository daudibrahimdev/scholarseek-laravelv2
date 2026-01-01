<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LearningSessionParticipant;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPackage;
use App\Models\LearningSession;
use App\Models\Package;
use Carbon\Carbon;

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
        // 1. Ambil paket mentee yang mau di-assign
        $userPackage = UserPackage::with('package')
            ->where('id', $userPackageId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending_assignment')
            ->firstOrFail();

        // 2. Ambil daftar Mentor yang available & approved
        // Kita ambil user-nya sekalian biar bisa nampilin nama & foto
        $mentors = Mentor::with('user')
            ->where('verification_status', 'verified') // atau 'verified' sesuaikan DB lu
            ->where('is_available', true)
            ->get();

        return view('mentee.mentor.assign', compact('userPackage', 'mentors'));
    }

    /**
     * Simpan pilihan mentor mentee.
     */
    public function assignMentor(Request $request)
    {
        $request->validate([
            'user_package_id' => 'required|exists:user_packages,id',
            'mentor_id' => 'required|exists:mentors,id',
        ]);

        // Update UserPackage
        $userPackage = UserPackage::where('id', $request->user_package_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $userPackage->update([
            'mentor_id' => $request->mentor_id,
            'status' => 'active', // Aktifkan paketnya!
        ]);

        return redirect()->route('mentee.consultations.index')
        ->with('success', 'Permintaan bimbingan telah dikirim! Silakan tunggu konfirmasi dari mentor. Kami akan memberi tahu Anda jika mentor menyetujui atau menolak permintaan ini.');
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

        // Ambil sesi mendatang (start_time >= sekarang) di mana mentee ini terdaftar
        $sessions = LearningSessionParticipant::with(['session.mentor.user'])
            ->where('mentee_id', $menteeId)
            ->whereHas('session', function($query) {
                $query->where('start_time', '>=', Carbon::now())
                    ->orderBy('start_time', 'asc');
            })
            ->get();

        return view('mentee.sessions.upcoming', compact('sessions'));
    }
}

