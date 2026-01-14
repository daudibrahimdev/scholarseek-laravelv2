<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mentor;
use App\Models\Transaction;
use App\Models\UserPackage;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;


use App\Models\LearningSession;

use Illuminate\Support\Facades\Storage;

class MentorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mentorId = auth()->user()->mentorProfile->id;

        // 1. Statistik
        $totalSessions = LearningSession::where('mentor_id', $mentorId)
            ->whereMonth('start_time', now()->month)
            ->count();

        // PERBAIKAN: Ganti 'mentee_id' menjadi 'user_id'
        $activeMentees = UserPackage::where('mentor_id', $mentorId)
            ->where('remaining_quota', '>', 0)
            ->distinct('user_id') 
            ->count();

        $avgRating = 5.0; 

        // 2. Sesi Mendatang (Pastikan relasi di model LearningSession sudah benar)
        $upcomingSessions = LearningSession::with('participants.mentee') // Hapus .user di sini
            ->where('mentor_id', $mentorId)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->where('end_time', '>', now())
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

        // 3. Mentee Terbaru
        $latestMentees = UserPackage::with('mentee') // Cukup 'mentee' saja
            ->where('mentor_id', $mentorId)
            ->latest()
            ->take(5)
            ->get();

        // Jangan lupa path view-nya sesuai folder lo
        return view('mentor.dashboard.index', compact(
            'totalSessions', 
            'activeMentees', 
            'avgRating', 
            'upcomingSessions', 
            'latestMentees'
        ));
    }
    // debugging method daftar mentee dll
    public function listAssignedMentees(Request $request)
    {
        // 1. Ambil ID Mentor yang sedang login
        $mentor = Mentor::where('user_id', Auth::id())->firstOrFail();

        // 2. Hitung Statistik untuk Header
        $stats = [
            'total_active' => UserPackage::where('mentor_id', $mentor->id)
                                ->where('status', 'active')
                                ->count(),
            'total_pending' => UserPackage::where('mentor_id', $mentor->id)
                                ->where('status', 'pending_approval')
                                ->count(),
        ];

        // 3. Query Utama dengan Filter
        $query = UserPackage::with(['mentee', 'package'])
                    ->where('mentor_id', $mentor->id);

        // Filter berdasarkan status jika ada request dari dropdown filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 4. Ambil data dengan Pagination 10 per halaman
        $assignedMentees = $query->orderBy('requested_at', 'desc') // Filter berdasarkan permintaan paling baru
                                ->paginate(10);

        // 5. Kirim data dan stats ke view
        return view('mentor.mentees.index', compact('assignedMentees', 'stats'));
    }

    // untuk mentee konfirmasi pesanan
    public function approveMentee($id)
    {
        $package = UserPackage::findOrFail($id);
        $mentor = Mentor::where('user_id', Auth::id())->firstOrFail();
        
        // untuk make sure paket ini emang ditujukan untuk mentor yang lagi login
        if ($package->mentor_id !== $mentor->id) {
            return back()->with('error', 'Akses ditolak.');
        }

        $package->update([
            'status' => 'active',
            // Set tanggal expired otomatis berdasarkan durasi paket
            'expires_at' => now()->addDays($package->package->duration_days)
        ]);

        return back()->with('success', 'Mentee berhasil diterima! Silakan mulai bimbingan.');
    }

    // untuk reject 
    public function rejectMentee(Request $request, $id)
    {
        $package = UserPackage::findOrFail($id);
        $mentor = Mentor::where('user_id', Auth::id())->firstOrFail();
        
        // sama, ini untuk make sure keamanan aja
        if ($package->mentor_id !== $mentor->id) {
            return back()->with('error', 'Akses ditolak.');
        }

        // utk ambil alasan mentor, dari preset/template
        $reason = $request->reason_preset === 'other' ? $request->rejection_reason : $request->reason_preset;

        $package->update([
            'status' => 'rejected',
            'mentor_id' => null, // Lepas mentor agar mentee bisa pilih orang lain
            'rejection_reason' => $reason
        ]);

        return back()->with('success', 'Permintaan mentee telah ditolak.');
    }

    public function indexSchedule()
    {
        $mentor = Mentor::where('user_id', Auth::id())->firstOrFail();
        $now = now();

        // Query Sesi Mendatang
        $upcomingSessions = LearningSession::where('mentor_id', $mentor->id)
                            ->where('end_time', '>', $now)
                            ->orderBy('start_time', 'asc')
                            ->get();

        // Statistik
        $scheduledCount = $upcomingSessions->count();
        $finishedCount = LearningSession::where('mentor_id', $mentor->id)
                            ->where('end_time', '<=', $now)
                            ->count();

        // + kirim 'sessions'
        return view('mentor.schedule.index', [
            'upcomingSessions' => $upcomingSessions,
            'sessions' => $upcomingSessions, // Tambahin ini bro!
            'scheduledCount' => $scheduledCount,
            'finishedCount' => $finishedCount
        ]);
    }

    private function autoUpdateStatus($mentorId) {
    $now = now();
    LearningSession::where('mentor_id', $mentorId)
        ->where('status', 'scheduled')
        ->where('end_time', '<=', $now)
        ->update(['status' => 'completed']);
}

    public function cancelSession($id)
    {
        // Cari sesi berdasarkan ID
        $session = \App\Models\LearningSession::findOrFail($id);
        
        // Logic: Jika sesi 1on1 (Private), balikin kuota ke mentee
        if ($session->type == '1on1') {
            // Ambil mentee pertama yang terdaftar di sesi ini
            $participant = $session->participants()->first();
            if ($participant) {
                // Update sisa kuota di tabel user_packages
                \App\Models\UserPackage::where('user_id', $participant->mentee_id)
                    ->where('mentor_id', $session->mentor_id)
                    ->where('status', 'active')
                    ->increment('remaining_quota');
            }
        }

        // Ubah status jadi cancelled sesuai enum di database
        $session->update(['status' => 'cancelled']);

        return back()->with('success', 'Sesi berhasil dibatalkan dan kuota mentee telah dikembalikan.');
    }
     
    public function matchmakingIndex()
    {
        // Ambil paket yang statusnya 'open_request' (mentee sedang cari mentor) 
        // dan mentor_id-nya masih kosong
        $availableMentees = \App\Models\UserPackage::with(['mentee', 'package'])
            ->where('status', 'open_request') // Sesuaikan dengan Enum di DB lo
            ->whereNull('mentor_id')
            ->orderBy('requested_at', 'desc')
            ->get();

        return view('mentor.matchmaking.index', compact('availableMentees'));
    }

    public function claimMentee($id)
    {
        $mentor = \App\Models\Mentor::where('user_id', Auth::id())->firstOrFail();
        $package = \App\Models\UserPackage::findOrFail($id);

        // Keamanan: Cek apakah sudah diambil orang lain duluan
        if ($package->mentor_id !== null) {
            return back()->with('error', 'Maaf, mentee ini sudah diambil oleh mentor lain.');
        }

        // Update paket: Masukkan ID mentor dan ubah status jadi active
        $package->update([
            'mentor_id' => $mentor->id,
            'status' => 'active',
            'expires_at' => now()->addDays($package->package->duration_days)
        ]);

        return redirect()->route('mentor.mentees.index')->with('success', 'Berhasil mengambil mentee! Silakan hubungi mentee untuk jadwal bimbingan.');
    }
    public function indexFinance()
    {
        $mentor = Mentor::where('user_id', Auth::id())->firstOrFail();

        // 1. Ambil semua transaksi milik mentor ini
        $transactions = Transaction::where('mentor_id', $mentor->id)
                                   ->latest()
                                   ->get();

        // 2. Hitung Statistik
        
        // A. Total Penarikan (Uang yang sudah dicairkan)
        // type = 'withdrawal', status = 'paid'
        $totalWithdrawal = $transactions->where('type', 'withdrawal')
                                        ->where('status', 'paid')
                                        ->sum('amount');

        // B. Pendapatan Bulan Ini (Pemasukan dari pembelian paket mentee)
        // type = 'purchase', status = 'paid', bulan ini
        $incomeThisMonth = $transactions->where('type', 'purchase')
                                        ->where('status', 'paid')
                                        ->filter(function ($t) {
                                            return $t->created_at->isCurrentMonth();
                                        })
                                        ->sum('amount');

        // C. Pendapatan Tertunda (Belum cair/masih pending)
        // Bisa berupa purchase yang belum paid, atau withdrawal yang masih pending
        $pendingAmount = $transactions->where('status', 'pending')->sum('amount');
        $pendingCount = $transactions->where('status', 'pending')->count();

        // D. History Penarikan (Untuk tabel bawah)
        $withdrawalHistory = $transactions->where('type', 'withdrawal');

        return view('mentor.finance.index', compact(
            'transactions', 
            'totalWithdrawal', 
            'incomeThisMonth', 
            'pendingAmount', 
            'pendingCount',
            'withdrawalHistory'
        ));
    }
    public function indexReviews()
    {
        return view('mentor.reviews.index');
    }

    /**
     * Menampilkan form edit profil mentor.
     */
    public function editProfile()
    {
        $mentor = Mentor::where('user_id', Auth::id())->with('user')->firstOrFail();

        // untuk 
        $expertiseOptions = ScholarshipCategory::where('is_active', true)
                                               ->orderBy('name')
                                               ->get();

        return view('mentor.profile.edit', compact('mentor', 'expertiseOptions'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
 * Memperbarui data profil mentor.
 */
    // app/Http/Controllers/MentorController.php

// ...

    public function updateProfile(Request $request)
    {
        $mentor = Mentor::where('user_id', Auth::id())->firstOrFail();
        $user = Auth::user();

        // 1. Validasi (Ditambah validasi untuk full_address, is_available, dan expertise_areas)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string',
            'domicile_city' => 'required|string|max:100',
            'phone_number' => 'required|string|max:15',
            'full_address' => 'nullable|string', // Tambah validasi ini
            'is_available' => 'nullable|boolean', // Tambah validasi ini
            'expertise_areas' => 'nullable|array', // Tambah validasi ini
            'profile_picture' => 'nullable|image|max:2048', 
        ]);

        // 2. Update User (Nama & Email)
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // 3. Update Mentor Profile
        $data = $request->only(['bio', 'domicile_city', 'full_address', 'phone_number', 'expertise_areas']);
        
        // HANDLE IS_AVAILABLE (Checkbox)
        $data['is_available'] = $request->has('is_available');
        
        // Handle File Upload (Profile Picture)
        if ($request->hasFile('profile_picture')) {
            if ($mentor->profile_picture) {
                Storage::delete('public/' . $mentor->profile_picture);
            }
            
            $path = $request->file('profile_picture')->store('public/mentor/profile');
            $data['profile_picture'] = str_replace('public/', '', $path);
        }

        $mentor->update($data);

        return redirect()->route('mentor.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
