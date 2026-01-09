<div class="container-fluid sticky-top">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light border-bottom border-2 border-white">
            <a href="{{ route('home') }}" class="navbar-brand">
                <h1>iSTUDIO</h1>
            </a>
            <button type="button" class="navbar-toggler ms-auto me-0" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto">
                    <a href="{{ route('home') }}" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
                    
                    <a href="{{ route('mentee.packages.index') }}" 
                       class="nav-item nav-link {{ Request::routeIs('mentee.packages.*') ? 'active' : '' }}">Pricing & Paket</a>
                    
                    <a href="#" class="nav-item nav-link">Scholarships</a>
                    <a href="#" class="nav-item nav-link">Student Guide</a>
                    
                    @auth
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle {{ Request::routeIs('mentee.sessions.*') || Request::routeIs('mentee.consultations.*') ? 'active' : '' }}" data-bs-toggle="dropdown">Sesi & Konsultasi</a>
                            <div class="dropdown-menu m-0">
                                {{-- Menu Riwayat Konsultasi ditaruh di sini --}}
                                <a href="{{ route('mentee.consultations.index') }}" class="dropdown-item {{ Request::routeIs('mentee.consultations.index') ? 'active' : '' }}">Riwayat & Status Paket</a>
                                
                                <div class="dropdown-divider"></div>
                                
                                <a href="{{ route('mentee.sessions.upcoming') }}" class="dropdown-item {{ Request::routeIs('mentee.sessions.upcoming') ? 'active' : '' }}">Jadwal Sesi Mendatang</a>
                                <a href="{{ route('mentee.bookings.create') }}" class="dropdown-item {{ Request::routeIs('mentee.bookings.create') ? 'active' : '' }}">Ajukan Sesi Baru</a>
                                <a href="{{ route('mentee.sessions.history') }}" class="dropdown-item {{ Request::routeIs('mentee.sessions.history') ? 'active' : '' }}">Riwayat Kelas Selesai</a>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <a href="#" class="nav-item nav-link text-danger" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-item nav-link">Login</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
</div>