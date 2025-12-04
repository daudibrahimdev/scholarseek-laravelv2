@extends('layouts.mentor_master')

@section('sidebar')
    @include('mentor.partials.sidebar')
@endsection

@section('title', 'Mentor Dashboard')

@section('header_stats')
    {{-- Ambil Data Kinerja Mentor yang Sedang Login --}}
    @php
        // Kita asumsikan data kinerja mentor sudah dimuat di Controller dan tersedia
        // Jika belum, ini adalah placeholder yang akan diisi dengan data kinerja ($mentorStats)
        $totalSessions = 15;
        $activeMentees = 8;
        $avgRating = 4.7;
        $availabilityStatus = Auth::user()->mentorProfile->is_available ?? true; 
    @endphp

    <div class="row">
        {{-- Card 1: Total Sesi Bulan Ini --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Sesi Bulan Ini</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $totalSessions }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="ni ni-calendar-grid-58"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Total sesi yang dijadwalkan</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 2: Jumlah Mentee Aktif --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Mentee Aktif</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $activeMentees }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Sedang dalam bimbingan</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 3: Rata-rata Rating --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Rata-rata Rating</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($avgRating, 1) }} / 5.0</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Berdasarkan ulasan Mentee</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 4: Status Ketersediaan --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Status Anda</h5>
                            <span class="h2 font-weight-bold mb-0" 
                                  style="color: {{ $availabilityStatus ? '#2dce89' : '#f5365c' }} !important;">
                                {{ $availabilityStatus ? 'Tersedia' : 'Cuti' }}
                            </span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Kelola di Pengaturan Profil</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    {{-- Baris ini menggantikan baris GRAFIK di template Admin --}}
    
    <div class="row">
        {{-- Tabel 8 Kolom: Jadwal Sesi Mendatang --}}
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Jadwal Sesi Mendatang</h3>
                        </div>
                        <div class="col text-right">
                            <a href="#!" class="btn btn-sm btn-primary">Atur Jadwal</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama Mentee</th>
                                <th scope="col">Topik</th>
                                <th scope="col">Waktu</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ahmad Rizky</td>
                                <td>Review Esai LPDP</td>
                                <td>15 Des 2025, 14:00 WIB</td>
                                <td><span class="badge badge-success">Dikonfirmasi</span></td>
                            </tr>
                            <tr>
                                <td>Sarah Devi</td>
                                <td>Permintaan Sesi Baru</td>
                                <td>16 Des 2025, 10:00 WIB</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                            </tr>
                            {{-- Data Dinamis akan di-loop di sini --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        {{-- Tabel 4 Kolom: Daftar Mentee Terbaru --}}
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Mentee Terbaru</h3>
                        </div>
                        <div class="col text-right">
                            <a href="#!" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama Mentee</th>
                                <th scope="col">Rating Anda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Budi Santoso</th>
                                <td><i class="fas fa-star text-yellow"></i> 5.0</td>
                            </tr>
                            <tr>
                                <th scope="row">Citra Dewi</th>
                                <td><i class="fas fa-star text-yellow"></i> 4.5</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Baris kedua dari template Admin --}}
    <div class="row mt-5">
        {{-- Tabel Laporan Kinerja (Menggantikan Page Visits) --}}
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Laporan Kinerja Bulanan</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Di sini akan ada grafik performa dan ringkasan sesi yang telah diselesaikan.</p>
                </div>
            </div>
        </div>
    </div>
    
    @push('js')
        {{-- Scripts Chart.js dihapus karena kita tidak pakai grafik di sini --}}
    @endpush
@endsection