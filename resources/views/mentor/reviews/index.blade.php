@extends('layouts.mentor_master')

@section('sidebar')
    @include('mentor.partials.sidebar')
@endsection

@section('title', 'Ulasan & Rating')

@section('header_stats')
    {{-- Statistik Kinerja Rating Mentor --}}
    <div class="row">
        {{-- Card 1: Rata-rata Rating Global --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Rating Keseluruhan</h5>
                            <span class="h2 font-weight-bold mb-0">4.7 / 5.0</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 0.1</span>
                        <span class="text-nowrap">vs Bulan Lalu</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 2: Total Ulasan Diterima --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Ulasan</h5>
                            <span class="h2 font-weight-bold mb-0">95</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="ni ni-chat-round"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Dari semua sesi yang selesai</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 3: Rating Terbaru (Bulan Ini) --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Rating Bulan Ini</h5>
                            <span class="h2 font-weight-bold mb-0">4.9</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Kinerja terbaru</span>
                    </p>
                </div>
            </div>
        </div>
        
        {{-- Card 4: Kategori Ulasan Tertinggi --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Tertinggi di</h5>
                            <span class="h2 font-weight-bold mb-0">Review Esai</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Spesialisasi terkuat</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        
        {{-- BAGIAN 1: TABEL DETAIL ULASAN (8 KOLOM) --}}
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Ulasan Terbaru dari Mentee</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Rating</th>
                                <th scope="col">Mentee</th>
                                <th scope="col">Topik Sesi</th>
                                <th scope="col">Ulasan</th>
                                <th scope="col">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Ulasan Statis --}}
                            <tr>
                                <td>
                                    <i class="fas fa-star text-yellow"></i> 5.0
                                </td>
                                <td>Ahmad Rizky</td>
                                <td>Review Esai LPDP</td>
                                <td>Mentor sangat detail dan helpful! Sangat merekomendasikan.</td>
                                <td>Hari Ini</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-star text-yellow"></i> 4.5
                                </td>
                                <td>Sarah Devi</td>
                                <td>Konsultasi Awal</td>
                                <td>Sedikit terlambat, tapi bimbingannya jelas dan solutif.</td>
                                <td>2 Hari Lalu</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-star text-yellow"></i> 5.0
                                </td>
                                <td>Budi Santoso</td>
                                <td>Review Motivation Letter</td>
                                <td>Sangat profesional dan cepat membalas. Top!</td>
                                <td>1 Minggu Lalu</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a href="#!" class="btn btn-sm btn-outline-primary">Lihat Semua Ulasan</a>
                </div>
            </div>
        </div>
        
        {{-- BAGIAN 2: RINGKASAN RATING (4 KOLOM) --}}
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Distribusi Rating</h3>
                </div>
                <div class="card-body">
                    {{-- Placeholder Grafik Bar Rating (5 Bintang) --}}
                    <div class="mb-2">
                        <span>5 Bintang</span>
                        <div class="progress mb-1">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 70%;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">70%</div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <span>4 Bintang</span>
                        <div class="progress mb-1">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">20%</div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <span>3 Bintang</span>
                        <div class="progress mb-1">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 5%;" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100">5%</div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <span>2 Bintang</span>
                        <div class="progress mb-1">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 3%;" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100">3%</div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <span>1 Bintang</span>
                        <div class="progress mb-bar" role="progressbar" style="width: 2%;" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100">2%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        {{-- Tabel Laporan Kinerja Rating (Menggantikan Page Visits) --}}
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Tren Rating Berdasarkan Sesi</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Di sini akan ada tampilan grafik atau ringkasan performa rating dari waktu ke waktu.</p>
                </div>
            </div>
        </div>
    </div>
@endsection