<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\LearningSession;
use App\Models\LearningSessionParticipant;
use App\Models\UserPackage;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menteeId = Auth::id();
        
        // Ambil semua paket aktif Mentee yang masih punya kuota
        $activePackages = UserPackage::with('package', 'mentor.user')
                                    ->where('user_id', $menteeId)
                                    ->where('status', 'active')
                                    ->where('remaining_quota', '>', 0)
                                    ->get();

        // Mengarahkan ke view package_select
        return view('mentee.request.package_select', compact('activePackages')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'session_id' => 'required|exists:learning_sessions,id',
            // tambahkan field 'package_id' di form untuk mempermudah identifikasi
            'user_package_id' => 'required|exists:user_packages,id', 
        ]);
        
        $sessionId = $request->session_id;
        $userPackageId = $request->user_package_id;
        $menteeId = Auth::id();

        // 2. Transaksi Database (Wajib untuk kuota/transaksi)
        try {
            DB::beginTransaction();

            // A. Ambil Data Kunci
            $session = LearningSession::findOrFail($sessionId);
            $userPackage = UserPackage::where('id', $userPackageId)
                                      ->where('user_id', $menteeId)
                                      ->lockForUpdate() // Lock baris untuk mencegah double booking
                                      ->firstOrFail();

            // B. Validasi Ketersediaan dan Kuota (Logic Bisnis Skema A)
            
            // 1. Cek apakah kuota masih ada
            if ($userPackage->remaining_quota <= 0) {
                throw new \Exception("Kuota sesi Anda sudah habis.");
            }
            
            // 2. Cek apakah mentor di paket ini sudah di-assign
            if (!$userPackage->mentor_id) {
                throw new \Exception("Anda belum memilih atau di-assign Mentor utama untuk paket ini.");
            }

            // 3. Cek apakah session ini milik mentor yang di-assign ke userPackage
            if ($session->mentor_id != $userPackage->mentor_id) {
                 throw new \Exception("Sesi ini bukan milik Mentor utama yang ditugaskan kepada Anda.");
            }

            // 4. Cek apakah user sudah terdaftar di sesi ini
            if (LearningSessionParticipant::where('learning_session_id', $sessionId)->where('mentee_id', $menteeId)->exists()) {
                 throw new \Exception("Anda sudah terdaftar di sesi ini.");
            }

            // C. Pemotongan Kuota & Update Status
            $userPackage->remaining_quota -= 1;
            $userPackage->save();
            
            // D. Buat Record Enrollment (Participant)
            LearningSessionParticipant::create([
                'learning_session_id' => $sessionId,
                'mentee_id' => $menteeId,
                'transaction_id' => null, // Nanti diisi jika ada logic transaksi lebih lanjut
                'status' => 'registered', // Status terdaftar
                'notes' => $request->notes ?? null,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Booking berhasil! Kuota Anda berkurang 1 sesi.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Tangani error validasi (seperti kuota habis, mentor mismatch, dll.)
            throw ValidationException::withMessages([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * Menampilkan formulir booking sesi (Jadwal Mentor).
     */
    public function showBookingForm($userPackageId)
    {
        $menteeId = Auth::id();

        // 1. Ambil Paket Aktif Mentee
        $userPackage = UserPackage::with(['package', 'mentor.user'])
                                ->where('id', $userPackageId)
                                ->where('user_id', $menteeId)
                                ->where('status', 'active') // Harus paket aktif
                                ->where('remaining_quota', '>', 0) // Harus ada kuota
                                ->firstOrFail();

        $mentor = $userPackage->mentor;

        // 2. Ambil jadwal sesi MENTOR yang:
        //    a. Statusnya 'scheduled' (sudah dibuat mentor, belum di-booking)
        //    b. Waktunya belum terlewat
        $availableSessions = LearningSession::where('mentor_id', $mentor->id)
                                            ->where('status', 'scheduled') 
                                            ->where('start_time', '>', now())
                                            ->orderBy('start_time', 'asc')
                                            ->get();

        return view('mentee.booking.form', compact('userPackage', 'mentor', 'availableSessions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
