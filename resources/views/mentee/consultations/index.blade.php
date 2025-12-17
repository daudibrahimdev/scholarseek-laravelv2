@extends('layouts.mentee_master')

@section('title', 'Pusat Konsultasi Saya')

@section('content')
    <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Pusat Konsultasi</h1>
                <a href="{{ route('mentee.index') }}" class="h5 text-white">Dashboard</a>
                <i class="far fa-circle text-white px-2"></i>
                <a href="#" class="h5 text-white">Konsultasi</a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                
                {{-- SECTION 1: PAKET AKTIF & PENDING (DIHANDLE CONTROLLER) --}}
                <div class="col-lg-12">
                    <h2 class="mb-4 text-primary">Status Paket Bimbingan Anda</h2>
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="row g-4">
                        @forelse ($activePackages as $pkg)
                            {{-- Card untuk Paket AKTIF --}}
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow border-start border-5 border-success">
                                    <div class="card-body">
                                        <h5 class="card-title text-success">{{ $pkg->package->name }} (AKTIF)</h5>
                                        
                                        <div class="d-flex align-items-center mb-2">
                                            <img src="{{ $pkg->mentor->profile_picture ? asset('storage/' . $pkg->mentor->profile_picture) : asset('mentee_assets/img/team-default.jpg') }}" 
                                                 class="rounded-circle me-3" alt="Mentor Photo" style="width: 40px; height: 40px; object-fit: cover;">
                                            <div>
                                                <p class="card-text mb-0 small text-muted">Mentor:</p>
                                                <p class="card-text mb-0"><strong>{{ $pkg->mentor->user->name ?? 'Belum Ditentukan' }}</strong></p>
                                            </div>
                                        </div>
                                        
                                        <p class="card-text mb-3">Sisa Kuota: <span class="fw-bold fs-4 text-primary">{{ $pkg->remaining_quota }}</span> Sesi</p>
                                        
                                        {{-- Tombol Booking Sesi --}}
                                        @if ($pkg->remaining_quota > 0)
                                            {{-- Link ini akan mengarah ke halaman booking sesi mentor ini (akan dibuat) --}}
                                            <a href="#" class="btn btn-sm btn-primary disabled" aria-disabled="true">
                                                <i class="fas fa-calendar-alt me-1"></i> Booking Sesi Baru (WIP)
                                            </a>
                                        @else
                                             <button class="btn btn-sm btn-secondary disabled">Kuota Habis</button>
                                        @endif
                                    </div>
                                    <div class="card-footer bg-light border-0">
                                        Berakhir pada: {{ $pkg->expires_at ? $pkg->expires_at->format('d M Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- Jika tidak ada paket aktif/pending (Logic redirect pending ada di Controller) --}}
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    Anda belum memiliki paket bimbingan yang aktif atau sedang menunggu penugasan mentor.
                                    <a href="{{ route('mentee.packages.index') }}" class="alert-link">Beli Paket Sekarang</a> untuk memulai konsultasi.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- SECTION 2: SESI MENDATANG --}}
                <div class="col-lg-12 mt-5">
                    <h2 class="mb-4 text-primary">Sesi Konsultasi Mendatang</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Mentor</th>
                                    <th>Topik</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($upcomingSessions as $session)
                                <tr>
                                    <td>{{ $session->start_time->format('d M Y, H:i') }}</td>
                                    <td>{{ $session->mentor->user->name ?? '-' }}</td>
                                    <td>{{ $session->title }}</td>
                                    <td><span class="badge bg-success">Confirmed</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info disabled">Lihat Detail</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Anda belum memiliki sesi konsultasi yang terjadwal.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection