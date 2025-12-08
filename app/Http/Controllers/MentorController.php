<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mentor;
use App\Models\Transaction;
use App\Models\UserPackage;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class MentorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mentor.dashboard.index');
    }
    // debugging method daftar mentee dll
    public function listAssignedMentees()
    {
        // 1. Ambil ID Mentor yang sedang login
        $mentor = Mentor::where('user_id', Auth::id())->firstOrFail();

        // 2. Ambil data dari tabel user_packages 
        // Dimana mentor_id sesuai dengan mentor yang login
        $assignedMentees = UserPackage::with(['mentee', 'package']) // Eager load User(mentee) dan Paket
                                      ->where('mentor_id', $mentor->id)
                                      ->orderBy('created_at', 'desc')
                                      ->get();

        // 3. Kirim ke view
        return view('mentor.mentees.index', compact('assignedMentees'));
    }
    public function indexSchedule()
    {
        return view('mentor.schedule.index');
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
        return view('mentor.profile.edit', compact('mentor'));
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
    public function updateProfile(Request $request)
    {
        $mentor = Mentor::where('user_id', Auth::id())->firstOrFail();
        $user = Auth::user();

        // 1. Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string',
            'domicile_city' => 'required|string|max:100',
            'phone_number' => 'required|string|max:15',
            'profile_picture' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // 2. Update User (Nama & Email)
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // 3. Update Mentor Profile
        $data = $request->only(['bio', 'domicile_city', 'full_address', 'phone_number']);
        
        // Handle File Upload (Profile Picture)
        if ($request->hasFile('profile_picture')) {
            // Hapus foto lama jika ada
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
