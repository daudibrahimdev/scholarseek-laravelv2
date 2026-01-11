@extends('layouts.mentee_master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-between mb-4 d-print-none">
                <a href="{{ route('mentee.transactions.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Riwayat
                </a>
                <button onclick="window.print()" class="btn btn-success">
                    <i class="bi bi-printer me-2"></i>Cetak Invoice (PDF)
                </button>
            </div>

            {{-- Box Invoice --}}
            <div class="card shadow border-0 p-5" id="printableInvoice">
                <div class="row mb-5">
                    <div class="col-sm-6">
                        <h2 class="text-primary fw-bold mb-0">iSTUDIO</h2>
                        <p class="text-muted small">Scholarship & Mentoring Platform</p>
                        <div class="mt-4">
                            <h6 class="text-muted text-uppercase small fw-bold">Diterbitkan Untuk:</h6>
                            <h5 class="mb-1 text-dark">{{ $transaction->user->name }}</h5>
                            <p class="text-muted mb-0 small">{{ $transaction->user->email }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                        <h4 class="text-uppercase fw-bold mb-1">Invoice</h4>
                        <div class="mb-3">ID: <strong>#{{ strtoupper(substr($transaction->reference_id ?? $transaction->id, 0, 12)) }}</strong></div>
                        
                        <div class="bg-light p-3 rounded">
                            <div class="small text-muted mb-1 text-uppercase fw-bold">Status Pembayaran</div>
                            <h4 class="mb-0 {{ in_array($transaction->status, ['paid', 'success']) ? 'text-success' : 'text-warning' }} text-uppercase">
                                {{ $transaction->status }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless border-bottom">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="py-3 px-4">Deskripsi Paket</th>
                                <th class="py-3 px-4 text-center">Sesi</th>
                                <th class="py-3 px-4 text-end">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-bottom">
                                <td class="py-4 px-4">
                                    <h6 class="mb-1 fw-bold">{{ $transaction->package->name ?? 'Paket Bimbingan' }}</h6>
                                    <small class="text-muted">Akses bimbingan intensif dengan mentor pilihan.</small>
                                </td>
                                <td class="py-4 px-4 text-center align-middle">
                                    {{ $transaction->package->total_sessions ?? '0' }} Sesi
                                </td>
                                <td class="py-4 px-4 text-end align-middle fw-bold">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end mt-4">
                    <div class="col-sm-5 text-end">
                        <div class="d-flex justify-content-between px-2">
                            <span class="fw-bold">Total Tagihan:</span>
                            <span class="h4 text-primary fw-bold">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                        </div>
                        <p class="small text-muted mt-3">Metode Pembayaran: <span class="text-uppercase">{{ $transaction->payment_method ?? 'Manual Transfer' }}</span></p>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-top text-center text-muted small">
                    <p class="mb-0 text-primary fw-bold italic">Terima kasih telah mempercayakan persiapan studi Anda bersama iSTUDIO.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Sembunyikan semua elemen kecuali kontainer invoice */
    body * { visibility: hidden; }
    #printableInvoice, #printableInvoice * { visibility: visible; }
    #printableInvoice {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        box-shadow: none !important;
    }
    .d-print-none { display: none !important; }
}
</style>
@endsection