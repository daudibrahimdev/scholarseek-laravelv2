@extends('layouts.mentee_master')

@section('title', 'Cari Mentor Terbaik')

@section('content')
<style>
    /* Konsistensi Font & Warna Hitam Pekat */
    .text-dark-custom { color: #1a1a1a !important; }
    
    /* Hero Header - Centered & Clean */
    .hero-header-custom {
        margin-top: 0;
        padding-top: 6rem;
        padding-bottom: 6rem;
        background: linear-gradient(rgba(13, 107, 104, .9), rgba(13, 107, 104, .9)), url(../img/bg-hero.jpg) center center no-repeat;
        background-size: cover;
    }

    /* Sidebar Filter - Sembunyi Total */
    .filter-sidebar {
        position: fixed;
        top: 0;
        right: -450px;
        width: 350px;
        height: 100%;
        background: #fff;
        z-index: 2050;
        transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
        border-left: 1px solid #eee;
        visibility: hidden;
    }
    .filter-sidebar.active { 
        right: 0; 
        visibility: visible;
    }

    .filter-overlay-backdrop {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 2040;
        display: none;
    }
    .filter-overlay-backdrop.active { display: block; }

    /* Card Mentor - Full Center Alignment */
    .team-item {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: 0.3s;
        border: 1px solid #f0f0f0;
        background: #fff;
    }
    .team-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .mentor-badge {
        font-size: 0.7rem;
        padding: 4px 12px;
        border-radius: 20px;
        background: rgba(13, 107, 104, 0.1);
        color: #0d6b68;
        font-weight: 700;
        text-transform: uppercase;
    }
</style>

{{-- Hero Section - Rata Tengah --}}
<div class="container-fluid pb-5 bg-primary hero-header">
    <div class="container py-5">
        <div class="row g-3 justify-content-center">
            <div class="col-12 text-center">
                <h1 class="display-1 mb-0 animated zoomIn text-black">Temukan Mentor</h1>
            </div>
        </div>
    </div>
</div>

<div class="container py-5 text-dark-custom">
    
    {{-- Search Bar --}}
    <div class="row mb-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="col-lg-8 mx-auto">
            <form action="{{ route('mentee.mentors.index') }}" method="GET">
                <div class="input-group shadow-sm p-1 bg-white rounded-pill border border-2">
                    <input type="text" name="search" class="form-control border-0 rounded-pill ps-4 py-3 text-dark-custom" 
                           placeholder="Cari nama atau biografi mentor..." value="{{ request('search') }}">
                    <button class="btn btn-white text-primary fw-bold px-4 border-end" type="button" id="filterToggle">
                        <i class="bi bi-sliders me-2"></i> Filter
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 ms-2 fw-bold">Cari Mentor</button>
                </div>
            </form>
        </div>
    </div>

    {{-- List Mentor Grid --}}
    <div class="row g-4">
        @forelse($mentors as $mentor)
        <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
            <div class="team-item bg-white position-relative overflow-hidden h-100">
                <div class="position-relative">
                    <img class="img-fluid w-100" 
                         src="{{ $mentor->profile_picture ? asset('storage/' . $mentor->profile_picture) : asset('img/team-1.jpg') }}" 
                         alt="{{ $mentor->user->name }}" style="height: 320px; object-fit: cover;">
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm fw-bold text-white">Tersedia</span>
                    </div>
                </div>
                <div class="p-4 text-center"> {{-- Teks Kartu Rata Tengah --}}
                    <h5 class="fw-bold mb-1 text-dark-custom">{{ $mentor->user->name }}</h5>
                    <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill text-primary me-1"></i> {{ $mentor->domicile_city ?? 'ScholarSeek Mentor' }}</p>
                    
                    {{-- Badge Keahlian - Perbaikan: Menghapus json_decode() --}}
                    <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
                        @if($mentor->expertise_areas)
                            @php 
                                // Karena data lo sudah otomatis ter-cast menjadi array di model, kita nggak butuh json_decode lagi
                                $expertiseArray = is_array($mentor->expertise_areas) ? $mentor->expertise_areas : json_decode($mentor->expertise_areas, true);
                            @endphp
                            @foreach($expertiseArray as $areaId)
                                @php $cat = $scholarshipCategories->find($areaId); @endphp
                                @if($cat) <span class="mentor-badge shadow-sm">{{ $cat->name }}</span> @endif
                                @if($loop->iteration >= 2) @break @endif
                            @endforeach
                        @else
                            <span class="mentor-badge shadow-sm">General Mentor</span>
                        @endif
                    </div>

                    <p class="small text-dark-custom mb-4 px-2" style="line-height: 1.6;">
                        "{{ Str::limit($mentor->bio, 90, '...') }}"
                    </p>

                    <div class="d-grid gap-2">
                        <a href="{{ route('mentee.packages.index') }}" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">
                            Pilih Mentor Ini
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-search text-muted display-1"></i>
            <h4 class="mt-4 fw-bold text-dark-custom">Mentor Tidak Ditemukan</h4>
            <p class="text-muted">Gunakan kata kunci lain atau reset filter pencarian Anda.</p>
            <a href="{{ route('mentee.mentors.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold mt-3">Reset Pencarian</a>
        </div>
        @endforelse
    </div>
    
    {{-- Pagination Dinamis --}}
    <div class="row mt-5">
        <div class="col-12 d-flex justify-content-center">
            {{ $mentors->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- SIDEBAR FILTER --}}
<div class="filter-overlay-backdrop" id="filterBackdrop"></div>
<div id="filterSidebar" class="filter-sidebar shadow p-4 text-dark-custom">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h5 class="text-primary fw-bold m-0"><i class="bi bi-funnel-fill me-2"></i>Filter Pencarian</h5>
        <button type="button" class="btn-close shadow-none" id="closeFilter"></button>
    </div>

    <form action="{{ route('mentee.mentors.index') }}" method="GET">
        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

        <div class="mb-4">
            <h6 class="fw-bold small text-uppercase mb-3 border-start border-primary border-4 ps-2">Domisili Mentor</h6>
            <div style="max-height: 250px; overflow-y: auto;" class="pe-2">
                @foreach($cities as $city)
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="city[]" value="{{ $city }}" 
                           id="city_{{ Str::slug($city) }}" {{ is_array(request('city')) && in_array($city, request('city')) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-bold" for="city_{{ Str::slug($city) }}">{{ $city }}</label>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mb-4">
            <h6 class="fw-bold small text-uppercase mb-3 border-start border-primary border-4 ps-2">Keahlian Beasiswa</h6>
            @foreach($scholarshipCategories as $cat)
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="expertise[]" value="{{ $cat->id }}" 
                       id="exp_{{ $cat->id }}" {{ is_array(request('expertise')) && in_array($cat->id, request('expertise')) ? 'checked' : '' }}>
                <label class="form-check-label small fw-bold" for="exp_{{ $cat->id }}">{{ $cat->name }}</label>
            </div>
            @endforeach
        </div>

        <div class="d-grid gap-2 mt-5 pt-3 border-top text-center">
            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold shadow">Terapkan Filter</button>
            <a href="{{ route('mentee.mentors.index') }}" class="btn btn-link text-muted py-2 btn-sm fw-bold">Reset Semua</a>
        </div>
    </form>
</div>

<script>
    const filterSidebar = document.getElementById('filterSidebar');
    const filterBackdrop = document.getElementById('filterBackdrop');
    const filterToggle = document.getElementById('filterToggle');
    const closeFilter = document.getElementById('closeFilter');

    function toggleSidebar(show) {
        if(show) {
            filterSidebar.classList.add('active');
            filterBackdrop.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            filterSidebar.classList.remove('active');
            filterBackdrop.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

    filterToggle.addEventListener('click', () => toggleSidebar(true));
    closeFilter.addEventListener('click', () => toggleSidebar(false));
    filterBackdrop.addEventListener('click', () => toggleSidebar(false));
</script>
@endsection