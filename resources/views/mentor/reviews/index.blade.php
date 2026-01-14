@extends('layouts.mentor_master')

@section('sidebar')
    @include('mentor.partials.sidebar')
@endsection

@section('title', 'Ulasan & Rating')

@section('header_stats')
    <div class="row">
        {{-- Card 1: Rata-rata Rating Global --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0 small">Rating Keseluruhan</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($averageRating, 1) }} <small class="text-muted">/ 5.0</small></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        @if($ratingDiff > 0)
                            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ number_format($ratingDiff, 1) }}</span>
                        @elseif($ratingDiff < 0)
                            <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> {{ number_format(abs($ratingDiff), 1) }}</span>
                        @else
                            <span class="text-muted mr-2"><i class="fa fa-minus"></i> 0.0</span>
                        @endif
                        <span class="text-nowrap">vs Bulan Lalu</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 2: Total Ulasan --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0 small">Total Ulasan</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $totalReviews }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="ni ni-chat-round"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Ulasan Terverifikasi</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 3: Rating Bulan Ini --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0 small">Kinerja Bulan Ini</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($currentMonthRating, 1) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Berdasarkan ulasan terbaru</span>
                    </p>
                </div>
            </div>
        </div>
        
        {{-- Card 4: Top Category --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0 small">Kategori Terbaik</h5>
                            <span class="h4 font-weight-bold mb-0 text-truncate d-block" style="max-width: 150px;">
                                {{ $topCategory->name ?? '-' }}
                            </span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                <i class="fas fa-crown"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Rating Avg: {{ isset($topCategory) ? number_format($topCategory->avg_rating, 1) : '0.0' }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        
        {{-- BAGIAN 1: TABEL DETAIL ULASAN --}}
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Ulasan Terbaru</h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Rating</th>
                                <th scope="col">Mentee</th>
                                <th scope="col">Paket/Topik</th>
                                <th scope="col">Ulasan</th>
                                <th scope="col">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-star text-yellow mr-1"></i>
                                        <span class="font-weight-bold">{{ $review->rating }}.0</span>
                                    </div>
                                </td>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        {{-- Avatar Placeholder jika tidak ada foto --}}
                                        <span class="avatar avatar-sm rounded-circle mr-3 bg-light text-primary font-weight-bold">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </span>
                                        <div class="media-body">
                                            <span class="mb-0 text-sm">{{ $review->user->name }}</span>
                                        </div>
                                    </div>
                                </th>
                                <td>
                                    <span class="badge badge-dot mr-4">
                                        <i class="bg-info"></i> {{ Str::limit($review->userPackage->package->name ?? 'Custom Session', 20) }}
                                    </span>
                                </td>
                                <td>
                                    <p class="text-sm text-muted mb-0" style="white-space: normal; min-width: 200px;">
                                        {{ Str::limit($review->review, 80) }}
                                    </p>
                                </td>
                                <td>
                                    {{ $review->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="far fa-comment-dots fa-3x mb-3"></i>
                                        <p>Belum ada ulasan yang masuk.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination (Jika ingin ditambah nanti) --}}
                @if($reviews->count() > 10)
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        {{-- Logika pagination simple --}}
                    </nav>
                </div>
                @endif
            </div>
        </div>
        
        {{-- BAGIAN 2: DISTRIBUSI RATING (Dinamic Progress Bar) --}}
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Distribusi Rating</h3>
                        </div>
                        <div class="col text-right">
                            <small class="text-muted">Total: {{ $totalReviews }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Loop Distribusi Bintang 5 sampai 1 --}}
                    @foreach($distribution as $star => $data)
                    @php
                        // Warna progress bar beda-beda biar cantik
                        $color = match($star) {
                            5 => 'bg-success', // Hijau
                            4 => 'bg-info',    // Biru Muda
                            3 => 'bg-primary', // Biru Tua
                            2 => 'bg-warning', // Kuning
                            1 => 'bg-danger',  // Merah
                        };
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-sm font-weight-bold text-muted">{{ $star }} Bintang</span>
                            <span class="text-sm font-weight-bold">{{ $data['count'] }}</span>
                        </div>
                        <div class="progress progress-xs">
                            <div class="progress-bar {{ $color }}" role="progressbar" 
                                 style="width: {{ $data['percentage'] }}%;" 
                                 aria-valuenow="{{ $data['percentage'] }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Widget Tambahan: Tips Meningkatkan Rating --}}
            <div class="card shadow mt-4">
                <div class="card-header bg-transparent">
                    <h6 class="text-uppercase text-muted ls-1 mb-1">Tips Mentor</h6>
                    <h2 class="mb-0">Tingkatkan Performa</h2>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled my-1">
                        <li class="d-flex align-items-center mb-3">
                            <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow-sm mr-3">
                                <i class="ni ni-time-alarm"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark text-sm">Tepat Waktu</h6>
                                <p class="text-xs text-muted mb-0">Hadir 5 menit sebelum sesi dimulai.</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-center">
                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow-sm mr-3">
                                <i class="ni ni-active-40"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark text-sm">Respon Cepat</h6>
                                <p class="text-xs text-muted mb-0">Balas chat mentee maks 24 jam.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection