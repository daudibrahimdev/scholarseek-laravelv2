@extends('layouts.mentee_master')

@section('title', 'Pilih Paket Bimbingan')

@section('content')
    {{-- <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Investasi Masa Depanmu</h1>
                <a href="{{ route('home') }}" class="h5 text-white">Home</a>
                <i class="far fa-circle text-white px-2"></i>
                <a href="#" class="h5 text-white">Paket Bimbingan</a>
            </div>
        </div>
    </div> --}}

    <div class="container-fluid pb-5 bg-primary hero-header">
    <div class="container py-5">
        <div class="row g-3 justify-content-center"> <div class="col-lg-10 text-center"> <h1 class="display-1 mb-0 animated zoomIn text-black">Paket Bimbingan</h1>
            </div>
        </div>
    </div>
</div>

    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
                <h5 class="fw-bold text-primary text-uppercase">Pricing Plans</h5>
                <h1 class="mb-0">Pilih Paket Terbaik untuk Lolos Beasiswa Impian</h1>
            </div>
            
            {{-- Alert Notifikasi --}}
            @if(session('error'))
                <div class="alert alert-danger mb-4 text-center">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success mb-4 text-center">{{ session('success') }}</div>
            @endif

            <div class="row g-4 justify-content-center">
                @foreach($packages as $pkg)
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.{{ $loop->iteration }}s">
                        <div class="card h-100 shadow border-0 overflow-hidden">
                            <div class="card-header bg-light text-center pt-5 pb-4">
                                <h3 class="mb-0">{{ $pkg->name }}</h3>
                                <p class="text-muted mt-2 mb-0" style="font-size: 0.9rem;">{{ $pkg->description }}</p>
                            </div>
                            <div class="card-body text-center pb-5 pt-0">
                                <h2 class="display-5 mb-3">
                                    <small class="align-top" style="font-size: 22px; line-height: 45px;">Rp</small>{{ number_format($pkg->price, 0, ',', '.') }}
                                </h2>
                                
                                <div class="d-flex justify-content-center mb-4">
                                    <ul class="list-unstyled text-start">
                                        <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> 
                                            Kuota: <strong>{{ $pkg->quota_sessions > 0 ? $pkg->quota_sessions . ' Sesi' : 'Unlimited' }}</strong>
                                        </li>
                                        <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> 
                                            Tipe: {{ ucfirst($pkg->type) }}
                                        </li>
                                        <li class="mb-2"><i class="fa fa-clock text-primary me-2"></i> 
                                            Durasi: {{ $pkg->duration_days }} Hari
                                        </li>
                                        
                                        @if($pkg->type == 'private' || $pkg->type == 'hybrid')
                                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> Private 1-on-1 Mentoring</li>
                                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> Review Dokumen & Esai</li>
                                        @endif

                                        @if($pkg->type == 'group' || $pkg->type == 'hybrid')
                                            <li class="mb-2"><i class="fa fa-check text-primary me-2"></i> Akses Webinar Group</li>
                                        @endif
                                    </ul>
                                </div>

                                {{-- ALUR BARU: Menuju halaman ringkasan checkout --}}
                                <a href="{{ route('mentee.checkout.index', ['package_id' => $pkg->id]) }}" 
                                   class="btn btn-primary py-2 px-4 rounded-pill">
                                    Pilih Paket Ini
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection