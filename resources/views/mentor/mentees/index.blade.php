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
                                <th scope="col">Email</th>
                                <th scope="col">Status Sesi</th>
                                <th scope="col">Terakhir Aktif</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- DATA STATIS MENTEE --}}
                            <tr>
                                <td>Ahmad Rizky</td>
                                <td>ahmad.rizky@mail.com</td>
                                <td>
                                    <span class="badge badge-success">Sesi Dikonfirmasi</span>
                                </td>
                                <td>Hari Ini</td>
                                <td>
                                    <a href="#!" class="btn btn-sm btn-info">Lihat Detail</a>
                                    <a href="#!" class="btn btn-sm btn-warning">Sesi Baru</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Sarah Devi</td>
                                <td>sarah.devi@mail.com</td>
                                <td>
                                    <span class="badge badge-warning">Pending Konfirmasi</span>
                                </td>
                                <td>Kemarin</td>
                                <td>
                                    <a href="#!" class="btn btn-sm btn-info">Lihat Detail</a>
                                    <a href="#!" class="btn btn-sm btn-success">Setujui Sesi</a>
                                </td>
                            </tr>
                             <tr>
                                <td>Citra Dewi</td>
                                <td>citra.d@mail.com</td>
                                <td>
                                    <span class="badge badge-primary">Sesi Selesai</span>
                                </td>
                                <td>1 Minggu Lalu</td>
                                <td>
                                    <a href="#!" class="btn btn-sm btn-info">Lihat Detail</a>
                                    <a href="#!" class="btn btn-sm btn-warning">Sesi Baru</a>
                                </td>
                            </tr>
                            {{-- Data lainnya akan di-loop secara dinamis --}}
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