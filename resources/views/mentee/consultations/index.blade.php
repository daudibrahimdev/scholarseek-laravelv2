@extends('layouts.mentee_master')

@section('title', 'Riwayat & Status Bimbingan')

@section('content')
<style>
    .text-dark-custom { color: #1a1a1a !important; }
    
    .hero-header-custom {
        margin-top: 0;
        padding-top: 6rem;
        padding-bottom: 6rem;
        background: linear-gradient(rgba(13, 107, 104, .9), rgba(13, 107, 104, .9)), url(../img/bg-hero.jpg) center center no-repeat;
        background-size: cover;
    }

    .card-custom {
        border-radius: 15px;
        border: none;
        transition: 0.3s;
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 700;
    }
</style>

{{-- Hero Section - Centered Text --}}
<div class="container-fluid hero-header-custom mb-5">
    <div class="container py-5 text-center">
        <h1 class="display-4 text-white animated zoomIn fw-bold mb-3">Riwayat Bimbingan</h1>
        <p class="text-white opacity-75 mx-auto" style="max-width: 600px;">Pantau status pengajuan mentor dan kelola paket bimbingan aktif Anda di satu tempat.</p>
    </div>
</div>

<div class="container py-5 text-dark-custom">
    <div class="row">
        {{-- BAGIAN 1: STATUS PERMINTAAN MENTOR --}}
        <div class="col-12 mb-5">
            <h4 class="fw-bold mb-4">
                <i class="bi bi-hourglass-split me-2 text-primary"></i>Status Permintaan Mentor
            </h4>
            
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="row g-4">
                {{-- REVISI LOGIC: Tambahkan 'open_request' supaya hasil matchmaking muncul --}}
                @php
                    $pendingPkgs = $activePackages->whereIn('status', ['pending_approval', 'pending_assignment', 'open_request']);
                @endphp

                @forelse ($pendingPkgs as $pkg)
                    <div class="col-md-6">
                        <div class="card card-custom shadow-sm bg-light">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="flex-shrink-0">
                                    <div class="bg-white p-1 rounded-circle shadow-sm border border-2 border-primary">
                                        <img src="{{ $pkg->mentor && $pkg->mentor->profile_picture ? asset('storage/' . $pkg->mentor->profile_picture) : asset('img/default-avatar.jpg') }}" 
                                             class="rounded-circle" style="width: 70px; height: 70px; object-fit: cover;">
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-4">
                                    {{-- Badge Dinamis sesuai status di database --}}
                                    @if($pkg->status == 'open_request')
                                        <span class="badge bg-info text-white status-badge mb-2">Menunggu Mentor Mengambil</span>
                                    @else
                                        <span class="badge bg-warning text-dark status-badge mb-2">Menunggu Persetujuan</span>
                                    @endif
                                    
                                    <h5 class="fw-bold mb-1 text-dark-custom">{{ $pkg->package->name }}</h5>
                                    <p class="mb-1 small">Mentor: <span class="fw-bold">{{ $pkg->mentor->user->name ?? 'Mencari Mentor Terbaik...' }}</span></p>
                                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>Diajukan: {{ $pkg->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Hanya muncul kalau benar-benar tidak ada data DAN tidak sedang sukses matchmaking --}}
                    @if(!session('success'))
                    <div class="col-12 text-center py-5 bg-light rounded-3 shadow-sm border border-dashed">
                        <i class="bi bi-inbox text-muted display-4"></i>
                        <p class="text-muted mt-2">Tidak ada permintaan mentor yang sedang diproses.</p>
                    </div>
                    @endif
                @endforelse
            </div>
        </div>

        {{-- BAGIAN 2: RIWAYAT PAKET AKTIF --}}
        <div class="col-12">
            <h4 class="fw-bold mb-4">
                <i class="bi bi-shield-check me-2 text-primary"></i>Riwayat Paket Bimbingan
            </h4>
            <div class="table-responsive bg-white shadow-sm rounded-3">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Nama Paket</th>
                            <th>Mentor</th>
                            <th>Sisa Kuota</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activePackages->where('status', 'active') as $pkg)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark-custom">{{ $pkg->package->name }}</span><br>
                                    <small class="text-muted">ID: #PK-{{ $pkg->id }}</small>
                                </td>
                                <td>{{ $pkg->mentor->user->name ?? '-' }}</td>
                                <td><span class="badge bg-primary px-3 py-2 rounded-pill">{{ $pkg->remaining_quota }} Sesi</span></td>
                                <td><span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span></td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('mentee.bookings.create') }}" class="btn btn-sm btn-dark rounded-pill px-4 fw-bold shadow-sm">
                                        Buka Workspace
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-cart-x text-muted display-5"></i>
                                    <p class="text-muted mt-3">Belum ada riwayat paket aktif.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection