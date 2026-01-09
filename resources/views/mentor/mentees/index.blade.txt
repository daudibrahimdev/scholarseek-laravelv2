@extends('layouts.mentor_master')

@section('sidebar')
    @include('mentor.partials.sidebar')
@endsection

@section('title', 'Daftar Mentee Terhubung')

@section('header_stats')
    {{-- Card Statistik Khusus Daftar Mentee --}}
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Mentee Saat Ini</h5>
                            <span class="h2 font-weight-bold mb-0">9</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Pending Sesi Baru</h5>
                            <span class="h2 font-weight-bold mb-0">2</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="ni ni-bell-55"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Daftar Mentee Anda</h3>
                        </div>
                        <div class="col text-right">
                            <button class="btn btn-sm btn-outline-primary">Filter Status</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
                <th scope="col">Nama Mentee</th>
                <th scope="col">Paket Diambil</th>
                <th scope="col">Sisa Kuota</th>
                <th scope="col">Status Paket</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- LOOP DATA DINAMIS DARI DATABASE --}}
            @forelse ($assignedMentees as $data)
            <tr>
                <th scope="row">
                    <div class="media align-items-center">
                        <a href="#" class="avatar rounded-circle mr-3">
                            {{-- Gunakan foto profil mentee atau default --}}
                            <img alt="Image placeholder" src="{{ $data->mentee->profile_photo_url ?? asset('assets/img/theme/team-1-800x800.jpg') }}">
                        </a>
                        <div class="media-body">
                            <span class="mb-0 text-sm">{{ $data->mentee->name }}</span>
                            <br>
                            <small class="text-muted">{{ $data->mentee->email }}</small>
                        </div>
                    </div>
                </th>
                <td>
                    {{ $data->package->name }}
                </td>
                <td>
                    <span class="text-{{ $data->remaining_quota > 0 ? 'success' : 'danger' }} font-weight-bold">
                        {{ $data->remaining_quota }} Sesi
                    </span>
                </td>
                <td>
                    {{-- Badge Status --}}
                    @php
                        $badgeClass = match($data->status) {
                            'active' => 'success',
                            'pending_assignment' => 'warning',
                            'used_up' => 'danger',
                            'expired' => 'secondary',
                            default => 'primary'
                        };
                    @endphp
                    <span class="badge badge-dot mr-4">
                        <i class="bg-{{ $badgeClass }}"></i> {{ ucfirst(str_replace('_', ' ', $data->status)) }}
                    </span>
                </td>
                <td class="text-right">
                    <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" href="#">Lihat Detail Profil</a>
                            <a class="dropdown-item" href="#">Riwayat Sesi</a>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">
                    <p class="text-muted mb-0">Belum ada Mentee yang ditugaskan kepada Anda.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
                <div class="card-footer py-4">
                    {{-- Pagination (Placeholder) --}}
                </div>
            </div>
        </div>
    </div>
@endsection