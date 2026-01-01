@extends('layouts.mentee_master')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-5">
                <i class="bi bi-clock-history text-warning display-1 mb-4"></i>
                <h2 class="mb-3">Selesaikan Pembayaran</h2>
                <p class="text-muted">Silakan transfer sesuai nominal berikut melalui <strong>{{ $transaction->payment_method }}</strong>:</p>
                
                <div class="bg-light p-4 rounded mb-4">
                    <h1 class="text-primary mb-0">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</h1>
                    <small class="text-muted">Kode Transaksi: {{ $transaction->id }}</small>
                </div>

                <div class="alert alert-info small text-start">
                    <strong>Instruksi:</strong><br>
                    1. Transfer ke rekening fiktif iSTUDIO: 1234567890<br>
                    2. Simpan bukti transfer Anda.<br>
                    3. Klik tombol konfirmasi di bawah ini.
                </div>

                <form action="{{ route('mentee.checkout.confirm', $transaction->id) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 mt-3">
        Bayar
    </button>
</form>
                
                <a href="{{ route('mentee.index') }}" class="btn btn-link mt-3 text-muted">Bayar Nanti Saja</a>
            </div>
        </div>
    </div>
</div>
@endsection