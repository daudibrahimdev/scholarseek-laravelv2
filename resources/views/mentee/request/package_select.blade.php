@extends('layouts.mentee_master')

@section('title', 'Status Paket & Chat Mentor')

@section('content')
<div class="container py-5">
    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="text-primary text-uppercase">// Mentor Saya //</h6>
        <h1 class="mb-5">Pantau Kuota & Diskusi Jadwal</h1>
    </div>

    @if ($activePackages->isEmpty())
        <div class="alert alert-warning text-center wow fadeInUp" data-wow-delay="0.3s">
            <i class="bi bi-exclamation-triangle-fill"></i> Anda belum memiliki paket aktif. Silakan beli paket terlebih dahulu.
        </div>
    @else
        <div class="row g-4 justify-content-center">
            @foreach ($activePackages as $package)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.{{ $loop->iteration }}s">
                    <div class="card h-100 shadow-sm border-primary">
                        {{-- Header: Nama Paket --}}
                        <div class="card-header bg-primary text-white text-center py-3">
                            <h5 class="mb-0 text-white">{{ $package->package->name }}</h5>
                        </div>

                        <div class="card-body p-4">
                            {{-- Kuota --}}
                            <div class="text-center mb-4">
                                <small class="text-muted d-block text-uppercase">Sisa Kuota Sesi</small>
                                <strong class="display-4 text-primary">{{ $package->remaining_quota }}</strong>
                            </div>
                            
                            <hr>
                            
                            {{-- Info Mentor --}}
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-person-circle text-primary" style="font-size: 2rem;"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted d-block">Mentor Anda</small>
                                    @if ($package->mentor)
                                        <strong class="text-dark">{{ $package->mentor->user->name }}</strong>
                                    @else
                                        <span class="text-danger">Belum Terpilih</span>
                                    @endif
                                </div>
                            </div>
                            
                            <p class="small text-muted mb-0">
                                <i class="bi bi-info-circle me-1"></i> Silakan hubungi mentor via chat untuk menyepakati waktu bimbingan.
                            </p>
                        </div>

                        <div class="card-footer bg-white border-0 p-4 pt-0">
                            {{-- Tombol Chat (Skema 1: Diskusi Informal) --}}
                            <a href="#" class="btn btn-outline-primary w-100 py-2 mb-2" onclick="alert('Fitur Chat akan segera hadir. Sementara silakan hubungi Mentor via WhatsApp/Telegram.')">
                                <i class="bi bi-chat-dots-fill me-2"></i>Chat Mentor
                            </a>

                            {{-- Tombol Lihat Jadwal (Cek Hasil Input Mentor) --}}
                            <a href="{{ route('mentee.sessions.upcoming') }}" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-calendar-check me-2"></i>Lihat Jadwal Saya
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection