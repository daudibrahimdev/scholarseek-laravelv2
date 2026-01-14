@extends('layouts.mentee_master')

@section('title', 'Home')

@push('css')
<style>
    /* Card Mentor Custom */
    .mentor-card-custom {
        border-radius: 20px;
        border: none;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        background: #fff;
        transition: 0.3s;
    }
    .mentor-img-container {
        position: relative;
        height: 250px;
    }
    .mentor-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .mentor-status-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #008a4e;
        color: #fff;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: bold;
    }
    .mentor-info-body {
        padding: 20px;
        text-align: center;
    }
    .mentor-name {
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 5px;
        font-size: 1.2rem;
    }
    .mentor-location {
        font-size: 13px;
        color: #666;
        margin-bottom: 15px;
    }
    .mentor-tags .badge {
        background: #e6f3f2;
        color: #0d6b68;
        font-size: 10px;
        border: none;
        margin: 2px;
        padding: 6px 12px;
    }
    .mentor-bio {
        font-size: 13px;
        color: #444;
        font-style: italic;
        margin: 15px 0;
    }
    .btn-mentor-primary {
        background: #0d6b68;
        color: #fff;
        border-radius: 12px;
        font-weight: bold;
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: none;
    }
    .btn-mentor-outline {
        background: #fff;
        color: #1a1a1a;
        border: 1px solid #1a1a1a;
        border-radius: 12px;
        font-weight: bold;
        width: 100%;
        padding: 10px;
    }

    /* Scholarship Overlay Revisi */
    .project-overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        /* Linear gradient dari gelap di bawah ke transparan di atas */
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.1) 100%);
        transition: 0.5s;
    }
    .project-item:hover .project-overlay {
        background: rgba(13, 107, 104, 0.8);
    }
</style>
@endpush

@section('content')
    {{-- Hero Start - Tetap Standar ISTUDIO --}}
    <div class="container-fluid pb-5 hero-header bg-light mb-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center mb-5">
                <div class="col-lg-6">
                    <h1 class="display-1 mb-4 animated slideInRight">Every Dream <br>Is<span class="text-primary"> Worth</span> Discovering</h1>
                    <h5 class="d-inline-block border border-2 border-white py-3 px-5 mb-0 animated slideInRight">
                        Find scholarships and opportunities that bring your dreams closer</h5>
                </div>
                <div class="col-lg-6">
                    <div class="owl-carousel header-carousel animated fadeIn">
                        <img class="img-fluid" src="{{ asset('mentee_assets/img/hero-slider-1.png') }}" alt="">
                        <img class="img-fluid" src="{{ asset('mentee_assets/img/hero-slider-2.png') }}" alt="">
                        <img class="img-fluid" src="{{ asset('mentee_assets/img/hero-slider-3.png') }}" alt="">
                    </div>
                </div>
            </div>
            {{-- Point Features --}}
            <div class="row g-5 animated fadeIn">
                @php
                    $features = [
                        ['icon' => 'fa-search', 'title' => 'Temukan Beasiswa Impianmu'],
                        ['icon' => 'fa-users', 'title' => 'Terhubung dengan Mentor Berpengalaman'],
                        ['icon' => 'fa-graduation-cap', 'title' => 'Bangun Rencana Belajarmu'],
                        ['icon' => 'fa-globe', 'title' => 'Mulai Perjalanan Globalmu']
                    ];
                @endphp
                @foreach($features as $f)
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 btn-square border border-2 border-white me-3">
                            <i class="fa {{ $f['icon'] }} text-primary"></i>
                        </div>
                        <h5 class="lh-base mb-0">{{ $f['title'] }}</h5>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SECTION BEASISWA --}}
    <div class="container-fluid mt-5">
        <div class="container mt-5">
            <div class="row g-0">
                <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
                    <div class="d-flex flex-column justify-content-center bg-primary h-100 p-5">
                        <h1 class="text-white mb-5">Berbagai <span class="text-uppercase text-primary bg-light px-2">Program</span></h1>
                        <h4 class="text-white mb-0"> <span class="display-1">Beasiswa</span></h4>
                    </div>
                    <a href="{{ route('mentee.scholarships.index') }}" class="btn btn-light py-3 px-5 fw-bold rounded-pill shadow-sm">
                        Lihat Semua Beasiswa <i class="fa fa-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="col-lg-7">
                    <div class="row g-0">
                        @php
                            $projects = [
                                ['img' => 'project-1.png', 'title' => 'Global Short Program'],
                                ['img' => 'project-2.png', 'title' => 'Pascasarjana Global'],
                                ['img' => 'project-3.png', 'title' => 'S1 Global: Fully Funded'],
                                ['img' => 'project-4.png', 'title' => 'Beasiswa Nasional'],
                                ['img' => 'project-5.png', 'title' => 'Beasiswa SMP & SMA'],
                                ['img' => 'project-6.png', 'title' => 'Dan Banyak Lagi'],
                            ];
                        @endphp
                        @foreach($projects as $index => $item)
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.{{ $index+2 }}s">
                            <div class="project-item position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/'.$item['img']) }}" alt="">
                                <a class="project-overlay text-decoration-none text-center" href="{{ route('mentee.scholarships.index') }}">
                                    <h4 class="text-white fw-bold mb-0" style="font-family: inherit;">{{ $item['title'] }}</h4>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION STUDENT GUIDE --}}
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center mb-4">
                <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
                    <h1 class="mb-5">Baca <span class="text-uppercase text-primary bg-light px-2">Panduan Dokumen</span></h1>
                    <p>Langkah awal menuju keberhasilan aplikasi beasiswa adalah memastikan kelengkapan dokumen yang dipersyaratkan.</p>
                </div>
                <div class="col-lg-7">
                    <div class="row g-0">
                        <div class="col-md-6 wow fadeIn" data-wow-delay="0.2s">
                            <div class="service-item h-100 d-flex flex-column justify-content-center bg-primary p-4">
                                <img class="img-fluid w-100 mb-4" src="{{ asset('mentee_assets/img/Service-1.png') }}" alt="">
                                <h3 class="text-white">Panduan Administrasi</h3>
                            </div>
                        </div>
                        <div class="col-md-6 wow fadeIn" data-wow-delay="0.4s">
                            <div class="service-item h-100 d-flex flex-column justify-content-center bg-light p-4">
                                <img class="img-fluid w-100 mb-4" src="{{ asset('mentee_assets/img/Service-2.png') }}" alt="">
                                <h3>Kumpulan Esai</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-start">
                <a href="{{ route('mentee.student_guide.index') }}" class="btn btn-light py-3 px-5 fw-bold rounded-pill border">
                    Lihat Semua Dokumen <i class="fa fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- SECTION MENTOR REVISI --}}
    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <h1 class="mb-5">Konsultasikan dengan <span class="text-uppercase text-primary bg-white px-2 shadow-sm">Para Mentor</span></h1>
            <div class="row g-4">
                @forelse($featuredMentors as $mentor)
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="mentor-card-custom">
                        <div class="mentor-img-container">
                            <span class="mentor-status-badge">Tersedia</span>
                            <img src="{{ $mentor->profile_picture ? asset('storage/'.$mentor->profile_picture) : asset('mentee_assets/img/team-1.jpg') }}" alt="">
                        </div>
                        <div class="mentor-info-body">
                            <h5 class="mentor-name">{{ $mentor->user->name }}</h5>
                            <div class="mentor-location">
                                <i class="bi bi-geo-alt-fill text-primary me-1"></i> {{ $mentor->domicile_city ?? 'Jakarta Selatan' }}
                            </div>
                            
                            <div class="mentor-tags mb-3">
                                @php
                                    // Dummy categories for UI matching
                                    $tags = ['EXCHANGE PROGRAM', 'FULLY FUNDED'];
                                @endphp
                                @foreach($tags as $tag)
                                    <span class="badge">{{ $tag }}</span>
                                @endforeach
                            </div>

                            <p class="mentor-bio">"{{ Str::limit($mentor->bio, 60) }}"</p>

                            <a href="{{ route('mentee.packages.index') }}" class="btn btn-mentor-primary">Pilih Mentor Ini</a>
                        </div>
                    </div>
                </div>
                <div class="text-start">
                <a href="{{ route('mentee.student_guide.index') }}" class="btn btn-light py-3 px-5 fw-bold rounded-pill border">
                    Cari Mentor Mu! <i class="fa fa-arrow-right ms-2"></i>
                </a>
            </div>
                @empty
                <div class="col-12 text-center"><p class="text-muted">Mentor belum tersedia.</p></div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- PRICING SECTION --}}
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="text-center wow fadeIn" data-wow-delay="0.1s">
                <h1 class="mb-5">Our <span class="text-uppercase text-primary bg-light px-2">Pricing</span> Plan</h1>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Fitur</th>
                            <th>Group Masterclass</th>
                            <th>Private Class</th>
                            <th>Breakthrough Package</th>
                            <th>Ultimate Package</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="text-start">Harga</th>
                            <td>Rp. 70,000 / sesi</td>
                            <td>Rp. 300,000 / sesi</td>
                            <td>Rp. 2,500,000 / paket</td>
                            <td>Rp. 4,500,000 / paket</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection