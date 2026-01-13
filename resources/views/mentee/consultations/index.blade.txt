@extends('layouts.mentee_master')

@section('title', 'Riwayat & Status Permintaan')

@section('content')
    <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Riwayat Bimbingan</h1>
                <p class="text-white">Pantau status pengajuan mentor dan riwayat paket Anda</p>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            {{-- BAGIAN 1: STATUS PERMINTAAN MENTOR (YANG LAGI JALAN) --}}
            <div class="col-12 mb-5">
                <h4 class="fw-bold mb-4"><i class="fas fa-hourglass-half me-2 text-primary"></i>Status Permintaan Mentor</h4>
                
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

                <div class="row g-4">
                    @forelse ($activePackages->whereIn('status', ['pending_approval', 'pending_assignment']) as $pkg)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm bg-light">
                                <div class="card-body d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-white p-2 rounded-circle shadow-sm">
                                            <img src="{{ $pkg->mentor && $pkg->mentor->profile_picture ? asset('storage/' . $pkg->mentor->profile_picture) : asset('mentee_assets/img/team-default.jpg') }}" 
                                                 class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-4">
                                        <div class="badge bg-warning text-dark mb-1">Menunggu Persetujuan</div>
                                        <h5 class="mb-0">{{ $pkg->package->name }}</h5>
                                        <p class="text-muted small mb-0">Mentor: <strong>{{ $pkg->mentor->user->name ?? '-' }}</strong></p>
                                        <small class="text-muted">Diajukan pada: {{ $pkg->created_at->format('d M Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4 bg-light rounded">
                            <p class="text-muted mb-0">Tidak ada permintaan mentor yang sedang diproses.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- BAGIAN 2: RIWAYAT PAKET AKTIF & SELESAI --}}
            <div class="col-12">
                <h4 class="fw-bold mb-4"><i class="fas fa-history me-2 text-primary"></i>Riwayat Paket Bimbingan</h4>
                <div class="table-responsive bg-white shadow-sm rounded">
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
                                        <span class="fw-bold">{{ $pkg->package->name }}</span><br>
                                        <small class="text-muted">ID: #PK-{{ $pkg->id }}</small>
                                    </td>
                                    <td>{{ $pkg->mentor->user->name ?? '-' }}</td>
                                    <td><span class="badge bg-primary">{{ $pkg->remaining_quota }} Sesi</span></td>
                                    <td><span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i>Aktif</span></td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('mentee.bookings.create') }}" class="btn btn-sm btn-dark px-3">
                                            Buka Workspace
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <img src="{{ asset('mentee_assets/img/empty.png') }}" alt="" style="width: 80px;" class="mb-3 d-block mx-auto opacity-50">
                                        <p class="text-muted">Belum ada riwayat paket aktif.</p>
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