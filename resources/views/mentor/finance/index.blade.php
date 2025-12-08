@extends('layouts.mentor_master')

@section('sidebar')
    @include('mentor.partials.sidebar')
@endsection

@section('title', 'Pendapatan & Pembayaran')

@section('header_stats')
    {{-- Statistik Kinerja Finansial Mentor --}}
    <div class="row">
        {{-- Card 1: Pendapatan Tertunda --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Tertunda</h5>
                            <span class="h2 font-weight-bold mb-0">Rp {{ number_format($pendingAmount, 0, ',', '.') }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-danger mr-2">{{ $pendingCount }} Transaksi</span>
                        <span class="text-nowrap">Belum Selesai</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 2: Pendapatan Bulan Ini --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Bulan Ini</h5>
                            <span class="h2 font-weight-bold mb-0">Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Total Pemasukan</span>
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
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Ditarik</h5>
                            <span class="h2 font-weight-bold mb-0">Rp {{ number_format($totalWithdrawal, 0, ',', '.') }}</span>
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
        
        {{-- Card 4: Info --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Jadwal Cair</h5>
                            <span class="h2 font-weight-bold mb-0">Tgl 05</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Bulan berikutnya</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        
        {{-- TABEL SEMUA TRANSAKSI --}}
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Riwayat Transaksi</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Tipe</th>
                                <th scope="col">Nominal</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $trx)
                            <tr>
                                <td>{{ $trx->created_at->format('d M Y') }}</td>
                                <td>
                                    {{ Str::limit($trx->description ?? 'Tidak ada keterangan', 30) }}
                                    <br>
                                    <small class="text-muted">{{ $trx->reference_id ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($trx->type == 'purchase')
                                        <span class="text-success"><i class="fas fa-arrow-down"></i> Masuk</span>
                                    @else
                                        <span class="text-danger"><i class="fas fa-arrow-up"></i> Tarik</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $badges = [
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger',
                                            'cancelled' => 'secondary'
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $badges[$trx->status] ?? 'light' }}">
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada riwayat transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        {{-- TABEL PENARIKAN & INFO REKENING --}}
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Penarikan Dana</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted text-sm">Dana yang berstatus 'paid' (masuk) dapat ditarik sesuai jadwal.</p>
                    
                    {{-- Tombol Tarik Dana (Bisa dibuat form/modal nanti) --}}
                    <button class="btn btn-block btn-warning mt-3 mb-4" onclick="alert('Fitur Request Withdrawal akan segera hadir!')">
                        Ajukan Penarikan Dana
                    </button>
                    
                    <h6 class="heading-small text-muted mb-3">Info Rekening</h6>
                    <div class="p-3 bg-secondary rounded">
                        {{-- Ini masih statis, bisa diambil dari profil mentor nanti --}}
                        <p class="mb-0 font-weight-bold">Bank BCA</p>
                        <p class="mb-0 text-sm">A/N: {{ Auth::user()->name }}</p>
                        <p class="mb-0 text-sm">No: **** **** 1234</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- TABEL KHUSUS WITHDRAWAL (Opsional, jika mau dipisah) --}}
    @if($withdrawalHistory->count() > 0)
    <div class="row mt-5">
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Riwayat Penarikan Dana (Withdrawal)</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawalHistory as $wd)
                            <tr>
                                <td>{{ $wd->created_at->format('d M Y') }}</td>
                                <td>Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                                <td>{{ $wd->payment_method ?? 'Bank Transfer' }}</td>
                                <td><span class="badge badge-default">{{ $wd->status }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection