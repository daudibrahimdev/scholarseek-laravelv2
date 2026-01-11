@extends('layouts.mentor_master')

@section('sidebar')
    @include('mentor.partials.sidebar')
@endsection

@section('title', 'Matchmaking - Job Board')

@section('header_stats')
    {{-- Header Sederhana untuk Matchmaking --}}
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card card-stats mb-4 mb-xl-0 shadow border-0 bg-gradient-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-white mb-0">Kesempatan Bimbingan</h5>
                            <span class="h2 font-weight-bold mb-0 text-white">{{ $availableMentees->count() }} Mentee Mencari Mentor</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-white text-primary rounded-circle shadow">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <span class="text-white mr-2"><i class="fa fa-info-circle"></i></span>
                        <span class="text-nowrap text-white">Pilih mentee yang sesuai dengan keahlian beasiswa Anda.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    {{-- Notifikasi Sukses/Gagal --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
            <span class="alert-text">{{ session('success') }}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
            <span class="alert-text">{{ session('error') }}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col">
            <div class="card shadow border-0">
                <div class="card-header border-0 bg-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0 text-primary">Job Board Mentee</h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort">Nama Mentee</th>
                                <th scope="col" class="sort">Target Studi & Beasiswa</th>
                                <th scope="col" class="sort">Paket</th>
                                <th scope="col">Catatan / Motivasi</th>
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @forelse($availableMentees as $item)
                                <tr>
                                    <th scope="row">
                                        <div class="media align-items-center">
                                            <div class="avatar rounded-circle mr-3 bg-lighter">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div class="media-body">
                                                <span class="name mb-0 text-sm font-weight-bold text-dark">{{ $item->mentee->name }}</span>
                                            </div>
                                        </div>
                                    </th>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="badge badge-pill badge-primary mb-1" style="width: fit-content;">
                                                <i class="fas fa-graduation-cap"></i> {{ $item->target_scholarship ?? 'Beasiswa Umum' }}
                                            </span>
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt"></i> {{ $item->target_country ?? 'Belum ditentukan' }} 
                                                ({{ $item->target_degree ?? '-' }})
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-dot mr-4">
                                            <i class="bg-info"></i>
                                            <span class="status">{{ $item->package->name }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-wrap" style="max-width: 250px;">
                                            <small class="text-dark">{{ $item->request_note ?? 'Tidak ada catatan tambahan.' }}</small>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <form action="{{ route('mentor.matchmaking.claim', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success shadow-sm px-3" 
                                                    onclick="return confirm('Apakah Anda yakin ingin membimbing mentee ini?')">
                                                <i class="fas fa-hand-holding-heart mr-1"></i> Ambil Mentee
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" style="height: 150px;" class="mb-3">
                                        <h4 class="text-muted">Belum ada mentee yang mencari mentor saat ini.</h4>
                                        <p class="small text-muted">Cek halaman ini secara berkala untuk mendapatkan bimbingan baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Footer untuk Pagination jika diperlukan --}}
                <div class="card-footer py-4 bg-white border-0">
                    {{-- {{ $availableMentees->links() }} --}}
                </div>
            </div>
        </div>
    </div>
@endsection