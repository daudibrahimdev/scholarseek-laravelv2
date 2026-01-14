@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('header_stats')
    <div class="row">
        {{-- CARD 1: TOTAL MENTEE --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Mentee</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($totalMentee) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">User terdaftar sebagai mentee</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- CARD 2: MENTOR AKTIF --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Mentor Aktif</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($totalMentor) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Siap mengajar</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- CARD 3: BEASISWA TERDAFTAR --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Data Beasiswa</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($totalScholarship) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                <i class="fas fa-briefcase"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Program tersedia</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- CARD 4: ACTION NEEDED (MENTOR PENDING) --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Butuh Approval</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $pendingMentorCount }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-user-clock"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        @if($pendingMentorCount > 0)
                            <span class="text-danger mr-2"><i class="fas fa-arrow-up"></i> Urgent</span>
                        @else
                            <span class="text-success mr-2"><i class="fas fa-check"></i> Aman</span>
                        @endif
                        <span class="text-nowrap">Menunggu verifikasi</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        
        {{-- BAGIAN 1: TABEL APPROVAL MENTOR (Paling Penting buat Admin) --}}
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Pendaftaran Mentor Baru</h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                                <th scope="col">Tanggal Daftar</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingMentors as $mentor)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <span class="avatar avatar-sm rounded-circle mr-3 bg-light text-primary font-weight-bold">
                                            {{ substr($mentor->name, 0, 1) }}
                                        </span>
                                        <div class="media-body">
                                            <span class="mb-0 text-sm font-weight-bold">{{ $mentor->name }}</span>
                                        </div>
                                    </div>
                                </th>
                                <td>{{ $mentor->email }}</td>
                                <td>{{ $mentor->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge badge-dot mr-4">
                                        <i class="bg-warning"></i> Pending
                                    </span>
                                </td>
                                <td>
                                    {{-- Tombol Quick Approve (Optional, arahkan ke detail user) --}}
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-info">
                                        Review
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <span class="text-muted font-italic">Tidak ada pendaftaran mentor baru.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        {{-- BAGIAN 2: TRANSAKSI TERBARU (Ringkasan) --}}
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Transaksi Terbaru</h3>
                        </div>
                        <div class="col text-right">
                            {{-- <a href="#!" class="btn btn-sm btn-primary">See all</a> --}}
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">User</th>
                                <th scope="col">Paket</th>
                                <th scope="col">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                            <tr>
                                <th scope="row">
                                    {{ Str::limit($transaction->user->name ?? 'User', 10) }}
                                </th>
                                <td>
                                    {{ $transaction->package->name ?? '-' }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="text-success font-weight-bold">Rp {{ number_format($transaction->amount / 1000) }}k</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <small class="text-muted">Belum ada transaksi.</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Shortcut Links --}}
            {{-- <div class="card shadow mt-4">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Jalan Pintas</h6>
                    <a href="{{ route('admin.scholarship.create') }}" class="btn btn-block btn-primary text-white mb-2">
                        <i class="ni ni-fat-add mr-1"></i> Tambah Beasiswa Baru
                    </a>
                    <a href="{{ route('admin.documents.create') }}" class="btn btn-block btn-info text-white">
                        <i class="ni ni-cloud-upload-96 mr-1"></i> Upload Dokumen
                    </a>
                </div>
            </div> --}}

        </div>
    </div>
@endsection