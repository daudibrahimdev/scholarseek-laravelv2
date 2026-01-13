<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\UserPackage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman ringkasan sebelum bayar
     */
    public function index($package_id)
    {
        $package = Package::findOrFail($package_id);
        return view('mentee.checkout.index', compact('package'));
    }

    /**
     * Langkah 1: Mentee klik "Pilih Paket" -> Buat transaksi PENDING
     */
    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'payment_method' => 'required'
        ]);

        $user = Auth::user();
        $package = Package::findOrFail($request->package_id);

        // --- LOGIC REPURCHASE START ---
        
        // 1. Cek: Apakah mentee punya paket ini yang MASIH AKTIF (kuota > 0)?
        $activeExisting = UserPackage::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->whereIn('status', ['active', 'pending_assignment', 'pending_approval'])
            ->where('remaining_quota', '>', 0)
            ->first();

        if ($activeExisting) {
            return back()->with('error', 'Kamu masih memiliki paket ' . $package->name . ' yang aktif dengan sisa ' . $activeExisting->remaining_quota . ' sesi.');
        }

        // 2. Jika paket lama sudah 0 kuota atau expired, HAPUS recordnya 
        // agar pas nanti insert di confirmPayment gak bentrok UNIQUE KEY (user_id, package_id)
        UserPackage::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where(function($q) {
                $q->where('remaining_quota', '<=', 0)
                ->orWhereIn('status', ['used_up', 'expired']);
            })->delete();

        // --- LOGIC REPURCHASE END ---

        // Simpan transaksi PENDING seperti biasa
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'amount' => $package->price,
            'type' => 'purchase',
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'description' => 'Pembelian paket ' . $package->name,
            'reference_id' => 'TRX-' . time(),
        ]);

        return redirect()->route('mentee.checkout.success', $transaction->id);
    }

    /**
     * Langkah 2: Halaman Instruksi Bayar
     */
    public function success($transaction_id)
    {
        $transaction = Transaction::with('package')->findOrFail($transaction_id);
        return view('mentee.checkout.success', compact('transaction'));
    }

    /**
     * Langkah 3: Konfirmasi Bayar (Tombol "Saya Sudah Bayar")
     * Di sini baru UserPackage dibuat
     */
    public function confirmPayment($transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);
        
        if ($transaction->status !== 'pending') {
            return redirect()->route('mentee.consultations.index');
        }

        $package = Package::findOrFail($transaction->package_id);

        try {
            DB::beginTransaction();

            // 1. Update Transaksi jadi PAID
            $transaction->update(['status' => 'paid']);

            // 2. Buat Record Baru (UNIQUE KEY aman karena data lama sudah di-delete di function store)
            $userPackage = UserPackage::create([
                'user_id' => $transaction->user_id,
                'package_id' => $package->id,
                'initial_quota' => $package->quota_sessions,
                'remaining_quota' => $package->quota_sessions,
                'purchased_at' => now(),
                'expires_at' => $package->duration_days ? now()->addDays($package->duration_days) : null,
                'status' => 'pending_assignment', 
            ]);

            DB::commit();

            return redirect()->route('mentee.mentor.assign.form', ['user_package_id' => $userPackage->id])
                            ->with('success', 'Pembayaran Terkonfirmasi! Silakan pilih mentor Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal konfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    // untuk cancel
    public function cancel($transaction_id)
    {
        $transaction = Transaction::where('id', $transaction_id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        try {
            $transaction->update(['status' => 'cancelled']);

            // UBAH BARIS INI: Tambahkan '.index' di belakangnya
            return redirect()->route('mentee.packages.index') 
                ->with('success', 'Pembayaran paket berhasil dibatalkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan pembayaran: ' . $e->getMessage());
        }
    }
}