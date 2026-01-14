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
    // 1. CEK AUTH & ROLE (Keamanan agar Mentee/Mentor gak nyasar ke sini)
    if (Auth::check()) {
        $userRole = Auth::user()->role;

        if ($userRole == 'mentee') {
            return redirect()->route('mentee.index');
        } elseif ($userRole == 'mentor') {
            return redirect()->route('mentor.dashboard.index');
        }
        // Jika bukan mentee/mentor, berarti admin, lanjut ke bawah...
    } else {
        return redirect('login');
    }

    // 2. DATA STATISTIK (Hanya dieksekusi kalau dia ADMIN)
    $totalMentee = User::where('role', 'mentee')->count();
    $totalMentor = User::where('role', 'mentor')->where('is_active', 1)->count();
    $pendingMentorCount = Mentor::where('verification_status', 'pending')->count();
    $totalScholarship = Scholarship::count();

    // 3. DATA TABEL
    $pendingMentors = User::join('mentors', 'users.id', '=', 'mentors.user_id')
        ->where('mentors.verification_status', 'pending')
        ->select('users.name', 'users.email', 'mentors.created_at', 'mentors.id as mentor_id')
        ->latest('mentors.created_at')
        ->take(5)
        ->get();

    $recentTransactions = Transaction::with(['user', 'package'])
        ->latest()
        ->take(5)
        ->get();

    // 4. RETURN KE VIEW DENGAN DATA
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


