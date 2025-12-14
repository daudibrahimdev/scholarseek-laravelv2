@extends('layouts.mentee_master')

@section('title', 'Ajukan Permintaan Sesi Baru')

@section('content')
<div class="container py-5">
    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="text-primary text-uppercase">// Ajukan Sesi Baru //</h6>
        <h1 class="mb-5">Pilih Paket untuk Permintaan Sesi</h1>
    </div>

    @if ($activePackages->isEmpty())
        <div class="alert alert-warning text-center wow fadeInUp" data-wow-delay="0.3s">
            <i class="bi bi-exclamation-triangle-fill"></i> Anda tidak memiliki paket aktif dengan sisa kuota untuk mengajukan sesi.
        </div>
    @else
        <div class="row g-4 justify-content-center">
            @foreach ($activePackages as $package)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.{{ $loop->iteration }}s">
                    {{-- Menggunakan komponen Card khas Bootstrap/Istudio --}}
                    <div class="card h-100 shadow-sm border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">{{ $package->package->name }}</h5>
                        </div>
                        <div class="card-body">
                            
                            <p class="card-text">
                                Kuota Tersisa: 
                                <strong class="text-success h4">{{ $package->remaining_quota }}</strong> sesi
                            </p>
                            
                            <hr>
                            
                            <p>Mentor Terpilih:</p>
                            @if ($package->mentor)
                                <h6 class="text-dark">{{ $package->mentor->user->name }}</h6>
                                <p class="small text-muted">ID Mentor: {{ $package->mentor->id }}</p>
                            @else
                                <span class="text-danger">Belum Terpilih</span>
                            @endif
                            
                            <input type="hidden" name="mentor_id" value="{{ $package->mentor_id }}">
                        </div>
                        <div class="card-footer text-center">
                            {{-- Link yang sudah kita perbaiki route-nya --}}
                            <a href="{{ route('mentee.session.request.create', ['userPackageId' => $package->id]) }}" 
                                class="btn btn-primary w-100">
                                Ajukan Permintaan Sesi
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection