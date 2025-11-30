<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk transaksi update status
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Ambil Data Statistik Cepat
        $stats = [
            'total_users' => User::count(),
            'total_mentors' => User::where('role', 'mentor')->count(),
            'total_mentees' => User::where('role', 'mentee')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'pending_mentors' => Mentor::where('verification_status', 'pending')->count(), 
        ];
        
        // 2. Ambil Aplikasi Pending untuk Tabel Peninjauan (Prioritas Admin)
        $pendingApplications = Mentor::with('user')
                                    ->where('verification_status', 'pending')
                                    ->latest()
                                    ->get(); 

        // 3. Ambil Semua User (untuk Tabel Daftar User)
        // Menerapkan filtering berdasarkan role jika ada request
        $query = User::query();

        if ($request->filled('role') && $request->role != 'all') {
            $query->where('role', $request->role);
        }
        
        // Ambil data user dengan paginasi
        $users = $query->orderBy('created_at', 'desc')->paginate(10); 
        
        return view('admin.users.index', compact('stats', 'users', 'pendingApplications'));
    }


    // =======================================================
    // LOGIKA KHUSUS: APPROVE / REJECT MENTOR
    // =======================================================

    /**
     * Menyetujui pendaftaran mentor.
     */
    public function approveMentor(Mentor $mentor)
    {
        // 1. Validasi Status (Hanya bisa disetujui kalo statusnya masih pending)
        if ($mentor->verification_status != 'pending') {
            return back()->with('error', 'Status mentor sudah bukan pending.');
        }

        // 2. Gunakan Transaksi Database
        DB::transaction(function () use ($mentor) {
            // A. Update status di tabel mentors
            $mentor->update(['verification_status' => 'approved']);

            // B. Update role di tabel users menjadi 'mentor'
            $mentor->user->update(['role' => 'mentor']);
        });

        return redirect()->route('admin.users.index')
                         ->with('success', 'Aplikasi mentor ' . $mentor->user->name . ' telah disetujui!');
    }

    /**
     * Menolak pendaftaran mentor.
     */
    public function rejectMentor(Mentor $mentor)
    {
        // Langsung ubah status di tabel mentors
        $mentor->update(['verification_status' => 'rejected']);

        // Optional: Reset role di users ke 'mentee' jika sebelumnya belum pernah disetujui.
        // Kita tidak perlu mengubah role user, karena role-nya masih 'mentee' (default).
        // Jika nanti user tersebut ingin mendaftar lagi, dia bisa submit ulang formnya.

        return redirect()->route('admin.users.index')
                         ->with('success', 'Aplikasi mentor ' . $mentor->user->name . ' telah ditolak.');
    }


    // =======================================================
    // LOGIKA KHUSUS: AKTIVASI / DEAKTIVASI AKUN
    // =======================================================

    /**
     * Toggle status is_active (non-aktifkan/aktifkan).
     */
    public function toggleUserStatus(User $user)
    {
        // Admin tidak boleh menonaktifkan akunnya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        // Toggle status is_active
        $newStatus = !$user->is_active;
        $user->update(['is_active' => $newStatus]);

        $message = $newStatus ? 'diaktifkan kembali.' : 'dinonaktifkan.';

        return redirect()->route('admin.users.index')
                         ->with('success', 'Akun ' . $user->name . ' berhasil ' . $message);
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
