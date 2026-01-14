<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User; 

use App\Models\DocumentCategory;

use App\Models\Document;

use Illuminate\Support\Facades\Auth;

use App\Models\Transaction;

use App\Models\Mentor;

use App\Models\Scholarship;

class AdminController extends Controller
{
    public function index()
    {
        // 1. STATISTIK KARTU (Nama kolom di DB lo: 'role' dan 'is_active')
        $totalMentee = User::where('role', 'mentee')->count();
        
        // Mentor Aktif = User role mentor yang is_active = 1
        $totalMentor = User::where('role', 'mentor')->where('is_active', 1)->count();
        
        // Butuh Approval = Mentor yang verification_status-nya 'pending' di tabel MENTORS
        $pendingMentorCount = Mentor::where('verification_status', 'pending')->count();
        
        // Total Program Beasiswa
        $totalScholarship = Scholarship::count();

        // 2. DATA TABEL APPROVAL (Join ke Users buat ambil Nama & Email)
        $pendingMentors = User::join('mentors', 'users.id', '=', 'mentors.user_id')
            ->where('mentors.verification_status', 'pending')
            ->select('users.name', 'users.email', 'mentors.created_at', 'mentors.id as mentor_id')
            ->latest('mentors.created_at')
            ->take(5)
            ->get();

        // 3. DATA TABEL TRANSAKSI
        $recentTransactions = Transaction::with(['user', 'package'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalMentee',
            'totalMentor',
            'pendingMentorCount',
            'totalScholarship',
            'pendingMentors',
            'recentTransactions'
        ));
    }
        
    public function kategori_dokumen()
    {
        return view('admin.documents.category');
    }

    public function tambah_kategori(Request $request)
    {
        
        
    }
}


