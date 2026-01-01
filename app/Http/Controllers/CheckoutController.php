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

        // Cek apakah user sudah punya paket ini yang masih aktif/pending?
        $existingPackage = UserPackage::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->whereIn('status', ['active', 'pending_assignment'])
            ->first();

        if ($existingPackage) {
            return back()->with('error', 'Anda masih memiliki paket ' . $package->name . ' yang aktif atau belum di-assign.');
        }

        // Simpan transaksi dengan status PENDING
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'amount' => $package->price,
            'type' => 'purchase',
            'status' => 'pending', // Belum aktif
            'payment_method' => $request->payment_method,
            'description' => 'Pembelian paket ' . $package->name,
            'reference_id' => 'TRX-' . time(),
        ]);

        // Lempar ke halaman instruksi bayar
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
            
            // Pastikan transaksi masih pending biar gak diproses 2 kali
            if ($transaction->status !== 'pending') {
                return redirect()->route('mentee.consultations.index');
            }

            $package = Package::findOrFail($transaction->package_id);

            try {
                DB::beginTransaction();

                // 1. Update Status Transaksi jadi PAID
                $transaction->update(['status' => 'paid']);

                // 2. Buat Record di UserPackage agar paket aktif
                $userPackage = UserPackage::create([
                    'user_id' => $transaction->user_id,
                    'package_id' => $package->id,
                    'initial_quota' => $package->quota_sessions,
                    'remaining_quota' => $package->quota_sessions,
                    'purchased_at' => now(),
                    'expires_at' => $package->duration_days ? now()->addDays($package->duration_days) : null,
                    'status' => 'pending_assignment', // Sesuai skema: Belum pilih mentor
                ]);

                DB::commit();

                // Lempar ke halaman pilih mentor (image_4c4c06.png)
                return redirect()->route('mentee.mentor.assign.form', ['user_package_id' => $userPackage->id])
                                ->with('success', 'Pembayaran Terkonfirmasi! Sekarang silakan pilih mentor Anda.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal konfirmasi pembayaran: ' . $e->getMessage());
            }
        }
}