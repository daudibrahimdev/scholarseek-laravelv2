@extends('layouts.mentee_master')

@section('title', 'Pilih Mentor Utama')

@section('content')
<style>
    /* CSS Sidebar Filter */
    .filter-sidebar {
        position: fixed;
        top: 0;
        right: -300px;
        width: 300px;
        height: 100%;
        background: #fff;
        z-index: 1050;
        transition: 0.5s;
        padding: 20px;
        overflow-y: auto;
    }
    .filter-sidebar.active { right: 0; }

    /* Overlay Desain Consultant agar Klikable */
    .team-overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(13, 107, 104, .9);
        transition: .5s;
        opacity: 0;
        padding: 15px;
        text-align: center;
        cursor: pointer;
    }
    .team-item:hover .team-overlay { opacity: 1; }
    
    /* Efek Hover Card */
    .team-item { transition: 0.3s; border-radius: 15px; }
    .team-item:hover { transform: translateY(-10px); }
</style>

<div class="container-fluid pb-5 bg-primary hero-header">
    <div class="container py-5 text-center">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Pilih Mentor</h1>
        <p class="text-white small">Pilih mentor favoritmu atau gunakan fitur Matchmaking untuk dicarikan mentor terbaik.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center mb-5 mt-n5">
        <div class="col-lg-10">
            <div class="card shadow animated zoomIn border-0" style="border: 2px dashed #0d6b68 !important; background-color: #f8f9fa;">
                <div class="card-body text-center p-4">
                    <h4 class="text-primary mb-2"><i class="fas fa-magic me-2"></i> Malas Pilih Sendiri?</h4>
                    <p class="text-muted mb-3 small">Gunakan fitur <strong>Matchmaking</strong>. Mentor yang ahli di bidang studimu akan langsung mengambil permintaan bimbinganmu.</p>
                    {{-- Tombol Pemicu Modal --}}
                    <button type="button" data-bs-toggle="modal" data-bs-target="#matchmakingModal" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Gunakan Fitur Matchmaking</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <form action="" method="GET" class="d-flex flex-wrap gap-2">
                <input type="text" name="search" class="form-control flex-grow-1 shadow-sm" placeholder="Cari nama atau spesialisasi mentor..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="fas fa-search"></i></button>
                <button type="button" class="btn btn-outline-primary shadow-sm" id="filterToggle"><i class="fas fa-filter"></i> Filter</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($mentors as $mentor)
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                <div class="team-item position-relative overflow-hidden shadow-sm h-100 bg-white">
                    <img class="img-fluid w-100" src="{{ $mentor->profile_picture ? asset('storage/' . $mentor->profile_picture) : asset('mentee_assets/img/team-default.jpg') }}" alt="" style="height: 280px; object-fit: cover;">
                    
                    <form action="{{ route('mentee.mentor.assign.store') }}" method="POST" id="form-mentor-{{ $mentor->id }}">
                        @csrf
                        <input type="hidden" name="user_package_id" value="{{ $userPackage->id }}">
                        <input type="hidden" name="mode" value="manual">
                        <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">
                        
                        <div class="team-overlay" onclick="confirmSelection('{{ $mentor->id }}', '{{ $mentor->user->name }}')">
                            <small class="text-white mb-2">{{ $mentor->domicile_city ?? 'Global' }}</small>
                            <h5 class="text-white mb-2">{{ $mentor->user->name }}</h5>
                            <p class="text-white small px-2 mb-3" style="font-size: 12px;">{{ Str::limit($mentor->bio, 80) }}</p>
                            <span class="btn btn-light btn-sm rounded-pill px-3 fw-bold">Pilih Mentor Ini</span>
                        </div>
                    </form>

                    <div class="p-3 text-center border-top">
                        <h6 class="mb-1 fw-bold">{{ $mentor->user->name }}</h6>
                        <small class="text-primary" style="font-size: 11px;">
                            @if(is_array($mentor->expertise_areas))
                                {{ \App\Models\ScholarshipCategory::whereIn('id', $mentor->expertise_areas)->first()->name ?? 'Scholarship Expert' }}
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                <p class="text-muted">Mentor tidak ditemukan. Coba hapus filter atau cari nama lain.</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $mentors->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- MODAL MATCHMAKING DENGAN INPUT OPSIONAL --}}
<div class="modal fade" id="matchmakingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white"><i class="fas fa-bullseye me-2"></i> Detail Target Matchmaking</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('mentee.mentor.assign.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_package_id" value="{{ $userPackage->id }}">
                <input type="hidden" name="mode" value="auto">
                
                <div class="modal-body p-4">
                    <p class="small text-muted mb-4">Lengkapi data ini agar mentor tahu tujuanmu, atau kosongkan jika ingin berkonsultasi dulu dengan mentor.</p>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Jenjang Target (Opsional)</label>
                            <select name="target_degree" class="form-select">
                                <option value="">Belum Menentukan</option>
                                <option value="S1">S1 (Bachelor)</option>
                                <option value="S2">S2 (Master)</option>
                                <option value="S3">S3 (Doctoral)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Negara Tujuan (Opsional)</label>
                            <input type="text" name="target_country" class="form-control" placeholder="Contoh: Australia, Germany">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold">Beasiswa Target (Opsional)</label>
                            <input type="text" name="target_scholarship" class="form-control" placeholder="Contoh: LPDP, AAS, DAAD">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold">Catatan Untuk Mentor (Optional)</label>
                            <textarea name="request_note" class="form-control" rows="3" placeholder="Apa fokus bimbingan yang kamu inginkan?"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Aktifkan & Cari Mentor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="filterSidebar" class="filter-sidebar shadow">
    <form action="" method="GET">
        <h5 class="text-primary mb-4 border-bottom pb-2">Filter Mentor</h5>
        <div class="mb-4">
            <h6 class="fw-bold small">Domisili</h6>
            @php $cities = \App\Models\Mentor::whereNotNull('domicile_city')->distinct()->pluck('domicile_city'); @endphp
            @foreach($cities as $city)
            <div class="form-check mb-1">
                <input class="form-check-input" type="checkbox" name="city[]" value="{{ $city }}" id="city-{{ $loop->index }}" {{ is_array(request('city')) && in_array($city, request('city')) ? 'checked' : '' }}>
                <label class="form-check-label small" for="city-{{ $loop->index }}">{{ $city }}</label>
            </div>
            @endforeach
        </div>
        <div class="mb-4">
            <h6 class="fw-bold small">Keahlian Beasiswa</h6>
            @foreach(\App\Models\ScholarshipCategory::all() as $cat)
            <div class="form-check mb-1">
                <input class="form-check-input" type="checkbox" name="expertise[]" value="{{ $cat->id }}" id="exp-{{ $cat->id }}" {{ is_array(request('expertise')) && in_array($cat->id, request('expertise')) ? 'checked' : '' }}>
                <label class="form-check-label small" for="exp-{{ $cat->id }}">{{ $cat->name }}</label>
            </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-2 rounded-pill">Terapkan</button>
        <button type="button" class="btn btn-outline-secondary w-100 rounded-pill" id="closeFilter">Tutup</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const filterSidebar = document.getElementById('filterSidebar');
    document.getElementById('filterToggle').addEventListener('click', () => filterSidebar.classList.add('active'));
    document.getElementById('closeFilter').addEventListener('click', () => filterSidebar.classList.remove('active'));

    function confirmSelection(id, name) {
        Swal.fire({
            title: `Pilih ${name}?`,
            text: `Permintaanmu akan dikirim ke ${name} untuk disetujui.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6b68',
            confirmButtonText: 'Ya, Pilih Mentor',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-mentor-' + id).submit();
            }
        });
    }
</script>
@endsection