@extends('layouts.mentor_master')

@section('sidebar')
    @include('mentor.partials.sidebar')
@endsection

@section('title', 'Pendapatan & Pembayaran')

@section('header_stats')
    {{-- Statistik Kinerja Finansial Mentor --}}
    <div class="row">
        {{-- Card 1: Total Pendapatan yang Belum Dibayar --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Pendapatan Tertunda</h5>
                            <span class="h2 font-weight-bold mb-0">Rp 1.500.000</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-danger mr-2"> 5 Sesi</span>
                        <span class="text-nowrap">Belum Dibayarkan</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 2: Total Pendapatan Bulan Ini --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Pendapatan Bulan Ini</h5>
                            <span class="h2 font-weight-bold mb-0">Rp 4.250.000</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"> 15%</span>
                        <span class="text-nowrap">vs Bulan Lalu</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 3: Total Penarikan --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Penarikan Dana</h5>
                            <span class="h2 font-weight-bold mb-0">Rp 25.000.000</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Sejak Bergabung</span>
                    </p>
                </div>
            </div>
        </div>
        
        {{-- Card 4: Next Payment Date --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Jadwal Pembayaran Berikutnya</h5>
                            <span class="h2 font-weight-bold mb-0">05 Jan 2026</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Pembayaran otomatis sistem</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        
        {{-- TABEL TRANSAKSI PENDAPATAN (8 KOLOM) --}}
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Riwayat Pendapatan Sesi</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Deskripsi Sesi</th>
                                <th scope="col">Pendapatan Bersih</th>
                                <th scope="col">Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01 Des 2025</td>
                                <td>Review Esai LPDP - Mentee Ahmad</td>
                                <td>Rp 280.000</td>
                                <td><span class="badge badge-success">Dibayar</span></td>
                            </tr>
                            <tr>
                                <td>28 Nov 2025</td>
                                <td>Sesi Konsultasi Profil - Mentee Sarah</td>
                                <td>Rp 280.000</td>
                                <td><span class="badge badge-warning">Tertunda</span></td>
                            </tr>
                            <tr>
                                <td>15 Nov 2025</td>
                                <td>Group Masterclass: Beasiswa Chevening</td>
                                <td>Rp 650.000</td>
                                <td><span class="badge badge-success">Dibayar</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a href="#!" class="btn btn-sm btn-outline-primary">Lihat Semua Transaksi</a>
                </div>
            </div>
        </div>
        
        {{-- TABEL PENARIKAN & PENGATURAN REKENING (4 KOLOM) --}}
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Penarikan Dana & Rekening</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted text-sm">Dana tertunda akan dibayarkan setiap tanggal 5 bulan berikutnya.</p>
                    <button class="btn btn-block btn-warning mt-3 mb-4">Ajukan Penarikan Dana (Manual)</button>
                    
                    <h6 class="heading-small text-muted mb-3">Rekening Pembayaran</h6>
                    <div class="info-block">
                        <p class="mb-0">Bank BCA - A/N John Doe</p>
                        <p class="mb-0 text-sm">Nomor: 5432 1234 5678</p>
                    </div>
                    <a href="#!" class="btn btn-sm btn-link mt-2">Ubah Pengaturan Rekening</a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- TABEL RIWAYAT PENARIKAN --}}
    <div class="row mt-5">
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Riwayat Penarikan Dana</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Tanggal Penarikan</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Metode</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>05 Nov 2025</td>
                                <td>Rp 10.000.000</td>
                                <td>BCA</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>05 Okt 2025</td>
                                <td>Rp 8.000.000</td>
                                <td>BCA</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection