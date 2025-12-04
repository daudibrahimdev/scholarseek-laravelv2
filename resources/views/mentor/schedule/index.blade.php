@extends('layouts.mentor_master')

@section('sidebar')
    @include('mentor.partials.sidebar')
@endsection

@section('title', 'Atur Jadwal Sesi')

@section('header_stats')
    {{-- Statistik singkat jadwal --}}
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Jam Tersedia (Minggu Ini)</h5>
                            <span class="h2 font-weight-bold mb-0">12 Jam</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="ni ni-time-alarm"></i>
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
                            <h5 class="card-title text-uppercase text-muted mb-0">Permintaan Pending</h5>
                            <span class="h2 font-weight-bold mb-0">3</span>
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
        
        {{-- BAGIAN 1: KALENDER & KETERSEDIAAN --}}
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Atur Ketersediaan Anda</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Di sini akan ada tampilan kalender atau jadwal mingguan. Untuk saat ini, ini adalah *placeholder*.</p>
                    
                    {{-- Placeholder Kalender Sederhana --}}
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Hari</th>
                                    <th scope="col">Jam Tersedia</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Senin</td>
                                    <td>09:00 - 12:00 WIB</td>
                                    <td><a href="#!" class="btn btn-sm btn-info">Edit</a></td>
                                </tr>
                                <tr>
                                    <td>Selasa</td>
                                    <td>13:00 - 17:00 WIB</td>
                                    <td><a href="#!" class="btn btn-sm btn-info">Edit</a></td>
                                </tr>
                                <tr>
                                    <td>Rabu</td>
                                    <td>Tidak Tersedia</td>
                                    <td><a href="#!" class="btn btn-sm btn-success">Tambah</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- BAGIAN 2: PERMINTAAN SESI PENDING --}}
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Permintaan Sesi Baru</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Mentee</th>
                                <th scope="col">Waktu Diminta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Budi Santoso</td>
                                <td>17 Des 2025</td>
                            </tr>
                            <tr>
                                <td>Lina Putri</td>
                                <td>18 Des 2025</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a href="#!" class="btn btn-sm btn-primary">Lihat Semua Permintaan</a>
                </div>
            </div>
        </div>
    </div>
@endsection