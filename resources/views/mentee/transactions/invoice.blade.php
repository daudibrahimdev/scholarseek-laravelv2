@extends('layouts.mentee_master')

@section('title', 'Invoice Pembayaran')

@section('content')
<div class="container-fluid pb-5 bg-primary hero-header d-print-none">
    <div class="container py-5">
        <div class="row g-3 justify-content-center">
            <div class="col-12 text-center">
                <h1 class="display-1 mb-0 animated zoomIn text-black">Detail Invoice</h1>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-between mb-4 d-print-none">
                <a href="{{ route('mentee.transactions.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Riwayat
                </a>
                <button onclick="window.print()" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-printer me-2"></i>Cetak Invoice (PDF)
                </button>
            </div>

            {{-- Box Invoice --}}
            <div class="card shadow border-0 p-5" id="printableInvoice" style="border-radius: 20px;">
                <div class="row mb-5">
                    <div class="col-sm-6">
                        {{-- GANTI DARI H2 KE LOGO SCHOLARSEEK --}}
                        <img src="{{ asset('assets/img/logo-scholarseek.png') }}" 
                             alt="ScholarSeek Logo" 
                             style="height: 60px; width: auto; object-fit: contain;" 
                             class="mb-3">
                        <p class="text-muted small">Scholarship & Mentoring Platform</p>
                        
                        <div class="mt-4">
                            <h6 class="text-muted text-uppercase small fw-bold">Diterbitkan Untuk:</h6>
                            <h5 class="mb-1 text-dark">{{ $transaction->user->name }}</h5>
                            <p class="text-muted mb-0 small">{{ $transaction->user->email }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                        <h4 class="text-uppercase fw-bold mb-1" style="color: #0d6b68;">Invoice</h4>
                        <div class="mb-3">ID: <strong>#{{ strtoupper(substr($transaction->reference_id ?? $transaction->id, 0, 12)) }}</strong></div>
                        
                        <div class="bg-light p-3 rounded-3">
                            <div class="small text-muted mb-1 text-uppercase fw-bold">Status Pembayaran</div>
                            <h4 class="mb-0 {{ in_array($transaction->status, ['paid', 'success']) ? 'text-success' : 'text-warning' }} text-uppercase fw-bold">
                                {{ $transaction->status }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead style="background-color: #0d6b68;" class="text-white">
                            <tr>
                                <th class="py-3 px-4" style="border-top-left-radius: 10px;">Deskripsi Paket</th>
                                {{-- <th class="py-3 px-4 text-center">Sesi</th> --}}
                                <th class="py-3 px-4 text-end" style="border-top-right-radius: 10px;">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-bottom">
                                <td class="py-4 px-4">
                                    <h6 class="mb-1 fw-bold text-dark">{{ $transaction->package->name ?? 'Paket Bimbingan' }}</h6>
                                    <small class="text-muted">Akses bimbingan intensif dengan mentor pilihan di ScholarSeek.</small>
                                </td>
                                {{-- <td class="py-4 px-4 text-center align-middle">
                                    {{ $transaction->package->total_sessions ?? '0' }} Sesi
                                </td> --}}
                                <td class="py-4 px-4 text-end align-middle fw-bold text-dark">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end mt-4">
                    <div class="col-sm-5 text-end">
                        <div class="d-flex justify-content-between px-2 align-items-center">
                            <span class="fw-bold text-muted">Total Tagihan:</span>
                            <span class="h3 fw-bold" style="color: #0d6b68;">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="mt-3 px-2">
                            <small class="text-muted text-uppercase fw-bold">Metode Pembayaran:</small>
                            <p class="mb-0 fw-bold">{{ $transaction->payment_method ?? 'Manual Transfer' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-top text-center text-muted small">
                    <p class="mb-0 fw-bold" style="color: #0d6b68; font-style: italic;">
                        Terima kasih telah mempercayakan persiapan studi Anda bersama ScholarSeek.
                    </p>
                    <small>Invoice ini sah dan diterbitkan secara elektronik.</small>
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
        padding: 40px !important;
        box-shadow: none !important;
        border: none !important;
    }
    .d-print-none { display: none !important; }
    
    /* Memastikan warna background (bg-light/bg-primary) muncul saat diprint */
    .bg-light { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; }
    thead { background-color: #0d6b68 !important; -webkit-print-color-adjust: exact; }
    .text-white { color: #ffffff !important; -webkit-print-color-adjust: exact; }
}
</style>
@endsection