<div class="container-fluid sticky-top bg-white shadow-sm">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light py-2">
            {{-- BRAND LOGO --}}
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="{{ asset('assets/img/logo-scholarseek.png') }}" 
                     alt="ScholarSeek" 
                     style="height: 60px; width: auto; object-fit: contain;">
            </a>

            <button type="button" class="navbar-toggler ms-auto" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto align-items-center gap-2">
                    
                    {{-- 1. MENU UTAMA --}}
                    <a href="{{ route('home') }}" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('mentee.packages.index') }}" class="nav-item nav-link {{ Request::routeIs('mentee.packages.*') ? 'active' : '' }}">Pricing</a>

                    {{-- 2. DROPDOWN INFORMASI --}}
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pusat Informasi</a>
                        <div class="dropdown-menu border-0 shadow rounded-3">
    <a href="{{ route('mentee.scholarships.index') }}" class="dropdown-item px-3 py-2 btn-hover-white">
        <i class="bi bi-award me-2 text-primary"></i>Scholarships
    </a>
    <a href="{{ route('mentee.student_guide.index') }}" class="dropdown-item px-3 py-2 btn-hover-white">
        <i class="bi bi-book me-2 text-primary"></i>Student Guide
    </a>
    <a href="{{ route('mentee.mentors.index') }}" class="dropdown-item px-3 py-2 btn-hover-white">
        <i class="bi bi-search me-2 text-primary"></i>Jelajahi Mentor
    </a>    
</div>
                    </div>

                    @auth
                        {{-- 3. DROPDOWN SESI DENGAN NOTIFIKASI URGENT --}}
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle position-relative {{ Request::routeIs('mentee.sessions.*') || Request::routeIs('mentee.consultations.*') ? 'active' : '' }}" 
                               data-bs-toggle="dropdown"
                               @if($needsMentorAction) 
                                   data-bs-toggle="popover" 
                                   data-bs-trigger="hover focus" 
                                   data-bs-placement="bottom" 
                                   data-bs-content="Kamu belum memilih mentor untuk paket yang aktif!" 
                               @endif>
                                Sesi Saya
                                {{-- BULETAN MERAH NAVBAR - Diatur agar tidak menempel teks --}}
                                @if($needsMentorAction)
                                    <span class="position-absolute bg-danger border border-light rounded-circle" 
                                          style="padding: 4px; top: 12px; right: -2px;">
                                        <span class="visually-hidden">Perlu Tindakan</span>
                                    </span>
                                @endif
                            </a>
                            {{-- Min-width ditambah agar badge 'Pilih Mentor' tidak menabrak teks --}}
                            <div class="dropdown-menu border-0 shadow rounded-3" style="min-width: 220px;">
                                <span class="dropdown-header text-uppercase small fw-bold text-muted">Aktivitas</span>
                                
                                {{-- MENU STATUS PAKET --}}
                                <a href="{{ route('mentee.consultations.index') }}" class="dropdown-item px-3 py-2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Status Paket</span>
                                        @if($needsMentorAction)
                                            <span class="badge rounded-pill bg-danger animate__animated animate__pulse animate__infinite ms-3" 
                                                  style="font-size: 0.6rem; padding: 0.4em 0.7em;">
                                                Pilih Mentor!
                                            </span>
                                        @endif
                                    </div>
                                </a>

                                <a href="{{ route('mentee.sessions.upcoming') }}" class="dropdown-item px-3 py-2">Jadwal Mendatang</a>
                                <div class="dropdown-divider"></div>
                                <span class="dropdown-header text-uppercase small fw-bold text-muted">Arsip</span>
                                <a href="{{ route('mentee.sessions.history') }}" class="dropdown-item px-3 py-2">Riwayat Kelas</a>
                                <a href="{{ route('mentee.bookings.create') }}" class="dropdown-item px-3 py-2 text-primary fw-bold btn-hover-white">
                                    <i class="bi bi-plus-circle me-1"></i> Ajukan Sesi
                                </a>
                            </div>
                        </div>

                        {{-- 4. DROPDOWN AKUN & TRANSAKSI --}}
                        <div class="nav-item dropdown ms-lg-3">
                            <a href="#" class="nav-link dropdown-toggle btn btn-outline-light text-dark border px-3 rounded-pill" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> Akun
                            </a>
                            <div class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3">
                                <div class="px-3 py-2 border-bottom mb-2">
                                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                                    <div class="small text-muted" style="font-size: 11px;">{{ Auth::user()->email }}</div>
                                </div>
                                <a href="{{ route('mentee.profile.edit') }}" class="dropdown-item px-3 py-2">
                                    <i class="bi bi-person-circle me-2"></i> Edit Profil
                                </a>
                                <a href="{{ route('mentee.transactions.index') }}" class="dropdown-item px-3 py-2 {{ request()->routeIs('mentee.transactions.*') ? 'active' : '' }}">
                                    <i class="bi bi-receipt me-2"></i> Riwayat Transaksi
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item px-3 py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 ms-lg-3">Login</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
</div>

<style>
    .btn-hover-white:hover {
        color: white !important; 
        background-color: #0d6b68 !important; 
    }
    .btn-hover-white:hover i {
        color: white !important;
    }
</style>
{{-- SCRIPT INISIALISASI POPOVER --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })
    });
</script>