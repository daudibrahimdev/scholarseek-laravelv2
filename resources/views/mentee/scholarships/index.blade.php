@extends('layouts.mentee_master')

@section('title', 'Daftar Beasiswa')

@section('content')
    {{-- Hero Start --}}
    <div class="container-fluid pb-5 bg-primary hero-header">
        <div class="container py-5">
            <div class="row g-3 align-items-center">
                <div class="col-lg-6 text-center text-lg-start">
                    <h1 class="display-1 mb-0 animated slideInLeft text-black">Scholarships</h1>
                </div>
            </div>
        </div>
    </div>
    {{-- Hero End --}}

    <section class="scholarship-section py-5">
        <div class="container">
            <form action="{{ route('mentee.scholarships.index') }}" method="GET">
                <div class="d-flex flex-wrap align-items-center mb-4 gap-2">
                    <input type="text" name="search" class="form-control flex-grow-1" placeholder="Search by name, provider, or field..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <button type="button" class="btn btn-outline-primary" id="filterToggle">☰ Filters</button>
                </div>

                <div id="filterSidebar" class="filter-sidebar shadow">
                    <h5 class="text-primary mb-3">Filter Beasiswa</h5>
                    <div class="mb-3">
                        <h6>Degree Level</h6>
                        @foreach($categories as $cat)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $cat->id }}" id="cat{{ $cat->id }}" 
                                    {{ is_array(request('categories')) && in_array($cat->id, request('categories')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat{{ $cat->id }}">{{ $cat->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">Apply Filters</button>
                    <button type="button" class="btn btn-outline-primary w-100" id="closeFilter">Close</button>
                </div>
            </form>

            <div class="row g-4" id="scholarshipList">
                @forelse($scholarships as $s)
                    <div class="col-md-4">
                        <div class="card scholarship-card border-0 shadow h-100 position-relative">
                            <div class="card-body">
                                <h5 class="card-title text-primary">{{ $s->title }}</h5>
                                <p class="small text-muted mb-2">Provider: <strong>{{ $s->provider }}</strong></p>
                                <p class="card-text text-truncate-3">{{ Str::limit($s->description, 100) }}</p>

                                <div class="d-flex justify-content-between align-items-center mb-3" style="font-size:13px; color:#0d6b68;">
                                    <span><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($s->start_date)->format('d M Y') }}</span>
                                    <span class="{{ \Carbon\Carbon::parse($s->deadline)->isPast() ? 'text-danger' : '' }}">
                                        <i class="bi bi-hourglass-split"></i> {{ \Carbon\Carbon::parse($s->deadline)->format('d M Y') }}
                                    </span>
                                </div>

                                <a href="{{ $s->link_url }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">Learn More</a>

                                <div class="mt-3">
                                    @foreach($s->categories as $sc)
                                        <span class="badge bg-light text-primary border border-primary rounded-pill" style="font-size: 10px;">{{ $sc->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Tidak ada beasiswa yang ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $scholarships->links() }}
            </div>
        </div>
    </section>
@endsection

@push('js')
<script>
    const filterSidebar = document.getElementById('filterSidebar');
    document.getElementById('filterToggle').addEventListener('click', () => {
        filterSidebar.classList.add('active');
    });

    document.getElementById('closeFilter').addEventListener('click', () => {
        filterSidebar.classList.remove('active');
    });
</script>
@endpush