@extends('admin.layouts.app')

@section('title', 'Manage Users')

@section('header_stats')
    
    <div class="row">
        {{-- Card Total Users --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Pengguna</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $stats['total_users'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $stats['total_mentors'] }}</span>
                        <span class="text-nowrap">Mentor</span> | 
                        <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> {{ $stats['total_mentees'] }}</span>
                        <span class="text-nowrap">Mentee</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card Mentor Pending Review --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Mentor Pending</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $stats['pending_mentors'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-user-clock"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Perlu ditinjau dan disetujui</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card Total Admin --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Admin</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $stats['total_admins'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Pengelola Sistem</span>
                    </p>
                </div>
            </div>
        </div>
        
        {{-- Card Rata-rata Rating (Placeholder) --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Avg. Rating Mentor</h5>
                            <span class="h2 font-weight-bold mb-0">4.5 / 5.0</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Berdasarkan ulasan</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    {{-- ALERT SUKSES & ERROR --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="ni ni-like-2"></i></span>
            <span class="alert-inner--text">{{ session('success') }}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="ni ni-support-16"></i></span>
            <span class="alert-inner--text">{{ session('error') }}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- ======================================================= --}}
    {{-- BAGIAN A: TABEL PENINJAUAN MENTOR (PENDING APPLICATIONS) --}}
    {{-- ======================================================= --}}
    @if ($pendingApplications->isNotEmpty())
        <div class="row mt-5">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0 bg-warning">
                        <h3 class="mb-0 text-white">
                            <i class="fas fa-exclamation-triangle"></i> Aplikasi Mentor Baru ({{ $stats['pending_mentors'] }} Pending)
                        </h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Nama User</th>
                                    <th scope="col">Kredensial Utama</th>
                                    <th scope="col">Tgl Daftar</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingApplications as $app)
                                <tr>
                                    <th scope="row">
                                        {{ $app->user->name }}
                                        <p class="text-muted text-sm mb-0">{{ $app->user->email }}</p>
                                    </th>
                                    <td>
                                        <span class="badge badge-default">
                                            {{ $app->expertise_areas ? implode(', ', array_slice($app->expertise_areas, 0, 2)) : 'Belum diisi' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $app->created_at->format('d M Y') }}
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-review-{{ $app->id }}">
                                            Review Form
                                        </a>

                                        {{-- Tombol Approve --}}
                                        <a href="#" class="btn btn-sm btn-success" 
                                            onclick="event.preventDefault(); document.getElementById('approve-form-{{ $app->id }}').submit();">
                                            Approve
                                        </a>
                                        <form id="approve-form-{{ $app->id }}" action="{{ route('admin.users.mentor.approve', $app) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>

                                        {{-- Tombol Reject --}}
                                        <a href="#" class="btn btn-sm btn-danger" 
                                            onclick="event.preventDefault(); document.getElementById('reject-form-{{ $app->id }}').submit();">
                                            Reject
                                        </a>
                                        <form id="reject-form-{{ $app->id }}" action="{{ route('admin.users.mentor.reject', $app) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ======================================================= --}}
    {{-- BAGIAN B: TABEL DAFTAR SEMUA USER --}}
    {{-- ======================================================= --}}
    <div class="row mt-5">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Daftar Semua Pengguna ({{ $users->total() }})</h3>
                        </div>
                        <div class="col text-right">
                            {{-- Filtering by Role --}}
                            <form method="GET" class="form-inline">
                                <label class="mr-2 text-muted" for="roleFilter">Filter Role:</label>
                                <select name="role" id="roleFilter" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="all">Semua Role</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="mentor" {{ request('role') == 'mentor' ? 'selected' : '' }}>Mentor</option>
                                    <option value="mentee" {{ request('role') == 'mentee' ? 'selected' : '' }}>Mentee</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama & Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Status Akun</th>
                                <th scope="col">Terdaftar Sejak</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                            <tr>
                                <th scope="row">
                                    {{ $user->name }}
                                    <p class="text-muted text-sm mb-0">{{ $user->email }}</p>
                                </th>
                                <td>
                                    <span class="badge badge-primary">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-dot">
                                        <i class="bg-{{ $user->is_active ? 'success' : 'danger' }}"></i> {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="text-right">
                                    {{-- Tombol Toggle Status (Non-aktifkan/Aktifkan) --}}
                                    @if ($user->id !== Auth::id())
                                        <a href="#" class="btn btn-sm btn-{{ $user->is_active ? 'danger' : 'success' }}" 
                                            onclick="event.preventDefault(); document.getElementById('toggle-status-form-{{ $user->id }}').submit();">
                                            {{ $user->is_active ? 'Non-aktifkan' : 'Aktifkan' }}
                                        </a>
                                        <form id="toggle-status-form-{{ $user->id }}" action="{{ route('admin.users.toggle-status', $user) }}" method="POST" style="display: none;">
                                            @csrf
                                            {{-- Laravel menggunakan POST untuk toggle, bukan PUT/PATCH untuk route ini --}}
                                        </form>
                                    @else
                                        <span class="text-muted"><i class="fas fa-lock"></i> Sendiri</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada pengguna terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
    
    {{-- MODAL REVIEW MENTOR (untuk menampilkan Bio, CV Path, dll.) --}}
    @foreach ($pendingApplications as $app)
        <div class="modal fade" id="modal-review-{{ $app->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-review-{{ $app->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-white">Review Aplikasi Mentor: {{ $app->user->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">**Kredensial:** {{ $app->expertise_areas ? implode(', ', $app->expertise_areas) : 'Tidak diisi' }}</p>
                        <h6 class="heading-small text-muted mb-4">Bio & Motivasi</h6>
                        <p>{{ $app->bio }}</p>
                        <hr>
                        <h6 class="heading-small text-muted mb-4">Dokumen Pendukung</h6>
                        <p>
                            CV/Resume: 
                            @if ($app->cv_path)
                                <a href="{{ asset('storage/' . $app->cv_path) }}" target="_blank" class="btn btn-sm btn-primary">Lihat CV</a>
                            @else
                                <span class="text-danger">Tidak tersedia</span>
                            @endif
                        </p>
                        <p>
                            Motivation Letter: 
                            @if ($app->motivation_letter_path)
                                <a href="{{ asset('storage/' . $app->motivation_letter_path) }}" target="_blank" class="btn btn-sm btn-primary">Lihat Motivation Letter</a>
                            @else
                                <span class="text-danger">Tidak tersedia</span>
                            @endif
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <a href="#" class="btn btn-success" onclick="event.preventDefault(); document.getElementById('approve-form-{{ $app->id }}').submit();">Approve Aplikasi</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection