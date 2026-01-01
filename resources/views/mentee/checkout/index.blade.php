@extends('layouts.mentee_master')

@section('title', 'Ringkasan Pembayaran')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0 overflow-hidden">
                <div class="card-header bg-primary py-3">
                    <h5 class="text-white mb-0 text-center">Ringkasan Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    
                    {{-- 1. TAMPILKAN ERROR VALIDASI (Misal: Belum pilih metode bayar) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 2. TAMPILKAN ERROR SESI (Misal: Paket sudah punya) --}}
                    @if (session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <h6 class="text-muted text-uppercase small">Paket yang Dipilih</h6>
                        <h3 class="text-primary">{{ $package->name }}</h3>
                    </div>
                    
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Harga Paket</span>
                            <strong>Rp {{ number_format($package->price, 0, ',', '.') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0 text-success">
                            <span>Total Bayar</span>
                            <strong class="h4 mb-0">Rp {{ number_format($package->price, 0, ',', '.') }}</strong>
                        </li>
                    </ul>
                    
                    <form action="{{ route('mentee.checkout.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                        
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">Pilih Metode Pembayaran (Fiktif):</label>
                            <select name="payment_method" class="form-select border-primary" required>
                                <option value="" selected disabled>-- Pilih Pembayaran --</option>
                                <option value="Transfer BCA">Transfer Manual BCA</option>
                                <option value="GoPay">GoPay / QRIS</option>
                                <option value="OVO">OVO</option>
                            </select>
                        </div>

                        <div class="alert alert-warning small py-2">
                            <i class="bi bi-info-circle me-2"></i> Dengan mengklik tombol di bawah, Anda akan membuat pesanan resmi.
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">
                            Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('mentee.packages.index') }}" class="text-muted small">Batal dan kembali pilih paket</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection