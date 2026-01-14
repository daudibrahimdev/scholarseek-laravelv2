@extends('layouts.mentee_master')

@section('title', 'Edit Profil Saya')

@section('content')
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
</style>

{{-- Hero Section --}}
<div class="container-fluid pb-5 bg-primary hero-header">
    <div class="container py-5">
        <div class="row g-3 justify-content-center">
            <div class="col-12 text-center">
                <h1 class="display-1 mb-0 animated zoomIn text-black">Profil Saya</h1>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom shadow-sm">
                <div class="card-body p-5 text-dark-custom">
                    <h4 class="fw-bold mb-4"><i class="bi bi-person-gear me-2 text-primary"></i>Informasi Pribadi</h4>
                    
                    <form action="{{ route('mentee.profile.update') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control rounded-pill" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email (Hanya Baca)</label>
                                <input type="email" class="form-control rounded-pill bg-light" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nomor WhatsApp</label>
                                <input type="text" name="phone_number" class="form-control rounded-pill" value="{{ old('phone_number', $user->phone_number) }}" placeholder="Contoh: 08123456789">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Asal Universitas</label>
                                <input type="text" name="university" class="form-control rounded-pill" value="{{ old('university', $user->university) }}" placeholder="Nama Universitas saat ini">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Program Studi / Jurusan</label>
                                <input type="text" name="major" class="form-control rounded-pill" value="{{ old('major', $user->major) }}" placeholder="Contoh: Teknik Informatika">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Bio Singkat</label>
                                <textarea name="bio" class="form-control rounded-3" rows="4" placeholder="Ceritakan sedikit tentang dirimu dan target beasiswamu...">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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