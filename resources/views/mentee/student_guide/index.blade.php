@extends('layouts.mentee_master')

@section('title', 'Student Guide')

@section('content')
    {{-- Hero Start - Standar ISTUDIO --}}
    <div class="container-fluid pb-5 bg-primary hero-header">
        <div class="container py-5">
            <div class="row g-3 align-items-center">
                <div class="col-lg-6 text-center text-lg-start">
                    <h1 class="display-1 mb-0 animated slideInLeft text-black">Student Guide</h1>
                </div>
                <div class="col-lg-6 animated slideInRight">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center justify-content-lg-end mb-0">
                            <li class="breadcrumb-item"><a class="text-black" href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item text-secondary active" aria-current="page">Student Guide</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    {{-- Hero End --}}

    <section class="scholarship-section py-5">
        <div class="container">

            <form action="{{ route('mentee.student_guide.index') }}" method="GET">
                <div class="d-flex flex-wrap align-items-center mb-4 gap-2">
                    <input type="text" name="search" class="form-control flex-grow-1" 
                           placeholder="Cari panduan berdasarkan judul atau deskripsi..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <button type="button" class="btn btn-outline-primary" id="filterToggle">☰ Filters</button>
                </div>

                <div id="filterSidebar" class="filter-sidebar shadow">
                    <h5 class="text-primary mb-3">Filter Panduan</h5>
                    <div class="mb-3">
                        <h6>Kategori Dokumen</h6>
                        @foreach($categories as $cat)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" 
                                       value="{{ $cat->id }}" id="cat{{ $cat->id }}"
                                       {{ is_array(request('categories')) && in_array($cat->id, request('categories')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat{{ $cat->id }}">
                                    {{ $cat->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">Apply Filters</button>
                    <button type="button" class="btn btn-outline-primary w-100" id="closeFilter">Close</button>
                    <a href="{{ route('mentee.student_guide.index') }}" class="btn btn-link w-100 mt-2 btn-sm text-muted">Reset Filter</a>
                </div>
            </form>

            <div class="row g-4" id="scholarshipList">
                @forelse($documents as $doc)
                    <div class="col-md-4">
                        <div class="card scholarship-card border-0 shadow h-100">
                            <div class="card-body">
                                <h5 class="card-title text-primary">{{ $doc->title }}</h5>
                                <p class="card-text text-truncate-3">{{ $doc->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
                                
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>Read Guide
                                </a>

                                <span class="position-absolute" style="bottom:10px; right:15px; font-weight:bold; color:#0d6b68; font-size: 0.8rem;">
                                    <i class="bi bi-tag-fill me-1"></i> {{ $doc->category->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-folder-x display-1 text-muted"></i>
                        <p class="mt-3 text-muted">Belum ada dokumen panduan yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $documents->links() }}
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