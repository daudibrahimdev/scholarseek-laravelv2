<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand pt-0" href="{{ route('home') }}">
            <img src="{{ asset('assets/img/brand/blue.png') }}" class="navbar-brand-img" alt="ScholarSeek Logo">
        </a>

        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('mentor.dashboard') ? 'active' : '' }}" href="{{ route('mentor.dashboard') }}">
                        <i class="ni ni-tv-2 text-primary"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="ni ni-single-02 text-info"></i> Profil Saya
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="ni ni-calendar-grid-58 text-warning"></i> Kelola Jadwal
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="ni ni-chart-bar-32 text-success"></i> Lihat Performansi
                    </a>
                </li>
            </ul>

            <hr class="my-3">
            <h6 class="navbar-heading text-muted">Akses Cepat</h6>
            <ul class="navbar-nav mb-md-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fas fa-search"></i> Cari Beasiswa
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>