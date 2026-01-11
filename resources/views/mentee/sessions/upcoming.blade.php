@extends('layouts.mentee_master')

@section('content')
<div class="container py-5">
    {{-- Header Section --}}
    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="text-primary text-uppercase font-weight-bold">// Jadwal Belajar //</h6>
        <h1 class="mb-5">Sesi & Kelas Mendatang</h1>
    </div>

    <div class="row g-4">
        @forelse ($sessions as $item)
            @php
                // Akses model session untuk kemudahan pembacaan kode
                $session = $item->session;
                $status = $session->status;
                $hasUrl = !empty($session->url_meeting);
            @endphp
            
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="card h-100 shadow-sm border-0 overflow-hidden">
                    {{-- Status Header --}}
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-primary text-uppercase px-2 py-1">
                                {{ $session->type == '1on1' ? 'Private' : 'Group' }}
                            </span>
                            <small class="fw-bold">
                                <i class="bi bi-calendar3 me-1"></i> {{ $session->start_time->format('d M Y') }}
                            </small>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        {{-- Judul Sesi --}}
                        <h5 class="card-title mb-3 font-weight-bold text-dark">{{ $session->title }}</h5>
                        
                        {{-- Jam Sesi --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-clock-fill text-primary"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted d-block">Waktu Sesi</small>
                                <strong class="text-dark">{{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }} WIB</strong>
                            </div>
                        </div>

                        {{-- Nama Mentor --}}
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0">
                                <i class="bi bi-person-badge-fill text-primary"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted d-block">Mentor</small>
                                <strong class="text-dark">{{ $session->mentor->user->name }}</strong>
                            </div>
                        </div>

                        {{-- Badge Status Aktif (Ongoing/Scheduled) --}}
                        <div class="mb-4">
                            @if($status == 'ongoing')
                                <span class="badge bg-warning text-dark w-100 py-2">
                                    <span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>
                                    Sesi Sedang Berlangsung
                                </span>
                            @else
                                <span class="badge bg-info text-white w-100 py-2">
                                    Terjadwal (Akan Datang)
                                </span>
                            @endif
                        </div>

                        {{-- Deskripsi --}}
                        <div class="p-3 bg-light rounded small mb-2 border-start border-primary border-4">
                            <i class="bi bi-info-circle me-2 text-primary"></i>
                            {{ Str::limit($session->description, 100, '...') }}
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 p-4 pt-0">
                        {{-- LOGIKA TOMBOL GABUNG SESI --}}
                        @if(($status == 'scheduled' || $status == 'ongoing') && $hasUrl)
                            {{-- Link Aktif --}}
                            <a href="{{ $session->url_meeting }}" target="_blank" class="btn btn-primary w-100 py-2 fw-bold shadow-sm hover-push">
                                <i class="bi bi-camera-video me-2"></i>Gabung Sesi Sekarang
                            </a>
                        @elseif($status == 'ongoing' && !$hasUrl)
                            {{-- Ongoing tapi mentor belum input link --}}
                            <button class="btn btn-warning w-100 py-2 fw-bold" disabled title="Menunggu mentor memasukkan link meeting">
                                <i class="bi bi-exclamation-triangle me-2"></i>Link Belum Tersedia
                            </button>
                        @else
                            {{-- Kondisi Scheduled tapi link kosong --}}
                            <button class="btn btn-secondary w-100 py-2 fw-bold" disabled>
                                <i class="bi bi-lock-fill me-2"></i>Link Belum Dirilis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                </div>
                <h3 class="text-dark">Belum ada jadwal sesi untukmu.</h3>
                <p class="text-muted mx-auto" style="max-width: 500px;">
                    Mentor akan segera menjadwalkan sesi bimbingan setelah berdiskusi denganmu. Pantau terus halaman ini atau hubungi mentormu.
                </p>
                <a href="{{ url('/mentee/dashboard') }}" class="btn btn-outline-primary mt-3 px-4">Kembali ke Dashboard</a>
            </div>
        @endforelse
    </div>
</div>

<style>
    .hover-push:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    .card {
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection