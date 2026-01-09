@extends('layouts.mentee_master')

@section('content')
<div class="container py-5">
    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="text-primary text-uppercase">// Jadwal Belajar //</h6>
        <h1 class="mb-5">Sesi & Kelas Mendatang</h1>
    </div>

    <div class="row g-4">
        @forelse ($sessions as $item)
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-primary text-uppercase">
                                {{ $item->session->type == '1on1' ? 'Private' : 'Group' }}
                            </span>
                            <small><i class="bi bi-calendar3 me-1"></i> {{ $item->session->start_time->format('d M Y') }}</small>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">{{ $item->session->title }}</h5>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-clock-fill text-primary"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted d-block">Waktu Sesi</small>
                                <strong>{{ $item->session->start_time->format('H:i') }} - {{ $item->session->end_time->format('H:i') }} WIB</strong>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0">
                                <i class="bi bi-person-badge-fill text-primary"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted d-block">Mentor</small>
                                <strong>{{ $item->session->mentor->user->name }}</strong>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded small mb-4">
                            <i class="bi bi-info-circle me-2"></i>{{ Str::limit($item->session->description, 80) }}
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-4 pt-0">
                        {{-- FIX: Mengganti link_meeting menjadi url_meeting sesuai database --}}
                        @if($item->session->url_meeting)
                            <a href="{{ $item->session->url_meeting }}" target="_blank" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-camera-video me-2"></i>Gabung Sesi Sekarang
                            </a>
                        @else
                            <button class="btn btn-secondary w-100 py-2" disabled>Link Belum Tersedia</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                </div>
                <h4>Belum ada jadwal sesi untukmu.</h4>
                <p class="text-muted">Mentor akan segera menjadwalkan sesi bimbingan setelah berdiskusi denganmu via chat.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection