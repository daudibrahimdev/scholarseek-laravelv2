@extends('layouts.mentee_master')

@section('title', 'Riwayat & Status Bimbingan')

@section('content')
{{-- Logic agar variabel navbar tidak error --}}
@php
    $needsMentorAction = $activePackages->where('status', 'pending_assignment')->count() > 0;
@endphp

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .text-dark-custom { color: #1a1a1a !important; }
    
    .hero-header-custom {
        margin-top: 0;
        padding-top: 6rem;
        padding-bottom: 6rem;
        background: linear-gradient(rgba(13, 107, 104, .9), rgba(13, 107, 104, .9)), url(../img/bg-hero.jpg) center center no-repeat;
        background-size: cover;
    }

    .card-custom {
        border-radius: 15px;
        border: none;
        transition: 0.3s;
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 700;
    }

    /* Star Rating Style */
    .star-rating i {
        font-size: 2rem;
        cursor: pointer;
        color: #ddd;
        transition: 0.2s;
    }
    .star-rating i.bi-star-fill.active {
        color: #ffc107;
    }
</style>

{{-- Hero Section --}}
<div class="container-fluid pb-5 bg-primary hero-header">
    <div class="container py-5">
        <div class="row g-3 justify-content-center">
            <div class="col-12 text-center">
                <h1 class="display-1 mb-0 animated zoomIn text-black">Riwayat Bimbingan</h1>
            </div>
        </div>
    </div>
</div>

<div class="container py-5 text-dark-custom">
    <div class="row">
        {{-- BAGIAN 1: STATUS PERMINTAAN MENTOR --}}
        <div class="col-12 mb-5">
            <h4 class="fw-bold mb-4">
                <i class="bi bi-hourglass-split me-2 text-primary"></i>Status Permintaan Mentor
            </h4>
            
            <div class="row g-4">
                @php
                    $pendingPkgs = $activePackages->whereIn('status', ['pending_approval', 'pending_assignment', 'open_request', 'rejected']);
                @endphp

                @forelse ($pendingPkgs as $pkg)
                    <div class="col-md-6">
                        <div class="card card-custom shadow-sm bg-light">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-white p-1 rounded-circle shadow-sm border border-2 border-primary">
                                            <img src="{{ $pkg->mentor && $pkg->mentor->profile_picture ? asset('storage/' . $pkg->mentor->profile_picture) : asset('img/search2.png') }}" 
                                                 class="rounded-circle" style="width: 70px; height: 70px; object-fit: cover;">
                                        </div>
                                    </div>
                                    <div class="ms-4 flex-grow-1">
                                        @if($pkg->status == 'open_request')
                                            <span class="badge bg-info text-white status-badge mb-2">Matchmaking Aktif</span>
                                        @elseif($pkg->status == 'rejected')
                                            <span class="badge bg-danger text-white status-badge mb-2">Permintaan Ditolak</span>
                                        @elseif($pkg->status == 'pending_assignment')
                                            <span class="badge bg-secondary text-white status-badge mb-2">Belum Pilih Mentor</span>
                                        @else
                                            <span class="badge bg-warning text-dark status-badge mb-2">Menunggu Persetujuan</span>
                                        @endif
                                        
                                        <h5 class="fw-bold mb-0 text-dark-custom">{{ $pkg->package->name }}</h5>
                                        <p class="mb-0 small">Mentor: <span class="fw-bold">{{ $pkg->mentor->user->name ?? 'Mencari Mentor Terbaik...' }}</span></p>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $pkg->updated_at->format('d M Y') }}</small>
                                    
                                    @if(in_array($pkg->status, ['open_request', 'pending_approval', 'rejected']))
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold" 
                                                onclick="confirmCancel('{{ $pkg->id }}')">
                                            <i class="bi bi-x-circle me-1"></i> Batalkan & Pilih Ulang
                                        </button>
                                        <form id="cancel-form-{{ $pkg->id }}" action="{{ route('mentee.matchmaking.cancel', $pkg->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    @elseif($pkg->status == 'pending_assignment')
                                        <a href="{{ route('mentee.mentor.assign.form', ['user_package_id' => $pkg->id]) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                                            Pilih Mentor Sekarang
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 bg-light rounded-3 shadow-sm border border-dashed">
                        <i class="bi bi-inbox text-muted display-4"></i>
                        <p class="text-muted mt-2">Tidak ada permintaan mentor yang sedang diproses.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- BAGIAN 2: RIWAYAT PAKET BANTUAN --}}
        <div class="col-12">
            <h4 class="fw-bold mb-4">
                <i class="bi bi-shield-check me-2 text-primary"></i>Riwayat Paket Bimbingan
            </h4>
            <div class="table-responsive bg-white shadow-sm rounded-3">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Nama Paket</th>
                            <th>Mentor Pembimbing</th>
                            <th class="text-center">Sisa Kuota</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activePackages->whereIn('status', ['active', 'used_up', 'expired']) as $pkg)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark-custom">{{ $pkg->package->name }}</span><br>
                                    <small class="text-muted">ID: #PK-{{ $pkg->id }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-badge me-2 text-primary"></i>
                                        {{ $pkg->mentor->user->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary px-3 py-2 rounded-pill">{{ $pkg->remaining_quota }} Sesi</span>
                                </td>
                                <td>
                                    @if($pkg->status == 'active')
                                        <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>
                                    @elseif($pkg->status == 'used_up')
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">Sesi Berakhir</span>
                                    @else
                                        <span class="text-danger fw-bold">Expired</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($pkg->status == 'active')
                                        <a href="{{ route('mentee.bookings.create') }}" class="btn btn-sm btn-dark rounded-pill px-4 fw-bold shadow-sm">
                                            Buka Workspace
                                        </a>
                                    @elseif($pkg->status == 'used_up')
                                        {{-- Logic Pengecekan Review Berdasarkan Database --}}
                                        @php
                                            $myReview = \App\Models\MentorReview::where('user_package_id', $pkg->id)->first();
                                        @endphp
                                        
                                        @if(!$myReview)
                                            <button class="btn btn-sm btn-warning rounded-pill px-3 fw-bold shadow-sm" 
                                                    onclick="showRatingModal('{{ $pkg->id }}', '{{ $pkg->mentor_id }}', '{{ $pkg->mentor->user->name }}')">
                                                <i class="bi bi-star-fill me-1"></i> Beri Rating
                                            </button>
                                        @else
                                            <div class="d-flex flex-column align-items-end">
                                                <div class="text-warning small mb-1">
                                                    @for($i=1; $i<=5; $i++)
                                                        <i class="bi {{ $i <= $myReview->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="badge bg-light text-success border border-success rounded-pill px-2">Terulas</span>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat paket bimbingan aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmCancel(id) {
        Swal.fire({
            title: 'Batalkan Permintaan?',
            text: "Kamu bisa memilih ulang mentor lain setelah membatalkan ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d6b68',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Kembali',
            borderRadius: '15px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('cancel-form-' + id).submit();
            }
        })
    }

    function showRatingModal(packageId, mentorId, mentorName) {
        Swal.fire({
            title: 'Review Mentor',
            html: `
                <div class="text-center mb-3">
                    <p class="small text-muted mb-3">Bagaimana pengalaman bimbinganmu dengan <b>${mentorName}</b>?</p>
                    <div id="star-rating" class="star-rating d-flex justify-content-center gap-2 mb-3">
                        <i class="bi bi-star" data-value="1"></i>
                        <i class="bi bi-star" data-value="2"></i>
                        <i class="bi bi-star" data-value="3"></i>
                        <i class="bi bi-star" data-value="4"></i>
                        <i class="bi bi-star" data-value="5"></i>
                    </div>
                    <textarea id="swal-review" class="form-control rounded-3" placeholder="Ceritakan kesan bimbinganmu..." rows="3"></textarea>
                    <input type="hidden" id="swal-rating-value" value="0">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Kirim Review',
            confirmButtonColor: '#0d6b68',
            cancelButtonText: 'Nanti Saja',
            borderRadius: '15px',
            didOpen: () => {
                const stars = Swal.getHtmlContainer().querySelectorAll('.bi-star');
                const ratingValue = document.getElementById('swal-rating-value');
                
                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const val = parseInt(this.getAttribute('data-value'));
                        ratingValue.value = val;
                        
                        stars.forEach((s, index) => {
                            if (index < val) {
                                s.classList.replace('bi-star', 'bi-star-fill');
                                s.classList.add('active');
                            } else {
                                s.classList.replace('bi-star-fill', 'bi-star');
                                s.classList.remove('active');
                            }
                        });
                    });
                });
            },
            preConfirm: () => {
                const rating = document.getElementById('swal-rating-value').value;
                const review = document.getElementById('swal-review').value;
                if (rating == 0) {
                    Swal.showValidationMessage('Tolong berikan bintang rating dulu ya!');
                    return false;
                }
                return { rating: rating, review: review };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('mentee.reviews.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_package_id: packageId,
                        mentor_id: mentorId,
                        rating: result.value.rating,
                        review: result.value.review
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terima Kasih!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Terjadi kesalahan saat menyimpan review.', 'error');
                });
            }
        })
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            borderRadius: '15px'
        });
    @endif
</script>
@endsection