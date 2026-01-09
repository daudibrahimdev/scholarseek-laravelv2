@extends('layouts.mentee_master')

@section('title', 'Tugaskan Mentor Utama')

@section('content')
    <div class="container-fluid pb-5 bg-primary hero-header">
        <div class="container py-5">
            <div class="row g-3 align-items-center">
                <div class="col-12 text-center">
                    <h1 class="display-3 text-white mb-3 animated slideInLeft">Pilih Mentor Utama</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item"><a class="text-white" href="{{ route('mentee.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-secondary active" aria-current="page">Assign Mentor</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="text-center position-relative pb-3 mx-auto" style="max-width: 800px;">
                <h3 class="fw-bold text-primary text-uppercase">Paket Aktif: {{ $userPackage->package->name }}</h3>
                <p class="mb-4">Sisa Kuota: {{ $userPackage->remaining_quota }} sesi | Status: Menunggu Penugasan</p>
                <p class="lead">Pilih Mentor utama Anda yang paling sesuai dengan kebutuhan dan keahliannya. Mentor ini akan bertanggung jawab atas kuota sesi dalam paket Anda.</p>
            </div>
            
            @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('mentee.mentor.assign.store') }}">
                @csrf
                
                {{-- Hidden Input untuk ID Paket --}}
                <input type="hidden" name="user_package_id" value="{{ $userPackage->id }}">

                <div class="row g-4 justify-content-center">
                    @forelse ($mentors as $mentor)
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.{{ $loop->iteration }}s">
                            <div class="card team-item position-relative overflow-hidden h-100 shadow-sm border-{{ old('mentor_id') == $mentor->id ? 'primary' : 'light' }}">
                                
                                {{-- Card Body: Profil Mentor --}}
                                <div class="card-body text-center p-4">
                                    <img class="img-fluid rounded-circle mb-3" src="{{ $mentor->profile_picture ? asset('storage/' . $mentor->profile_picture) : asset('mentee_assets/img/team-default.jpg') }}" alt="" style="width: 100px; height: 100px; object-fit: cover;">
                                    
                                    <small class="mb-2 text-primary">{{ $mentor->domicile_city ?? 'Global' }}</small>
                                    <h4 class="lh-base text-dark">{{ $mentor->user->name }}</h4>
                                    
                                    {{-- Bio Singkat --}}
                                    <p class="text-muted small mb-3">{{ Str::limit($mentor->bio, 80) }}</p>
                                    
                                    {{-- Keahlian (Ambil dari expertise_areas array) --}}
                                    @if(is_array($mentor->expertise_areas))
                                    <p class="small text-start">
                                        **Spesialisasi:** @foreach($mentor->expertise_areas as $areaId)
                                            {{-- NOTE: Kita asumsikan ada helper/model untuk mengambil nama kategori dari ID --}}
                                            <span class="badge bg-secondary me-1">{{ \App\Models\ScholarshipCategory::find($areaId)->name ?? 'N/A' }}</span>
                                        @endforeach
                                    </p>
                                    @endif
                                </div>
                                
                                {{-- Card Footer: Tombol Pilih/Radio --}}
                                <div class="card-footer bg-white text-center border-0 p-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="mentor_id" id="mentor-{{ $mentor->id }}" value="{{ $mentor->id }}" required {{ old('mentor_id') == $mentor->id ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="mentor-{{ $mentor->id }}">
                                            Pilih Mentor Ini
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center">Saat ini tidak ada Mentor yang tersedia untuk ditugaskan.</div>
                        </div>
                    @endforelse
                </div>
                
                @error('mentor_id')
                    <div class="alert alert-danger mt-4 text-center">Anda wajib memilih satu Mentor sebelum melanjutkan.</div>
                @enderror

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary btn-lg py-3 px-5 fw-bold rounded-pill">Konfirmasi Pilihan Mentor</button>
                </div>
            </form>
        </div>
    </div>
    @endsection