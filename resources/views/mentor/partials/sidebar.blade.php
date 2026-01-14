<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('mentor.dashboard.index') }}">
                {{-- LOGO MANUAL HTML/CSS --}}
                <div class="d-flex align-items-center justify-content-center">
                    {{-- Icon Topi (Nucleo Icon) --}}
                    <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow-sm mr-2" style="width: 32px; height: 32px;">
                        <i class="ni ni-hat-3" style="font-size: 0.85rem;"></i>
                    </div>
                    {{-- Text Logo --}}
                    <div class="d-flex flex-column text-left">
                        <span class="font-weight-900 text-uppercase" style="font-size: 1.1rem; letter-spacing: 0.5px; color: #5e72e4; line-height: 1;">
                            Scholar<span style="color: #172b4d;">Seek</span>
                        </span>
                    </div>
                </div>
            </a>
            
            <div class="ml-auto">
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-pin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="navbar-inner">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('mentor.dashboard.index') ? 'active' : '' }}" href="{{ route('mentor.dashboard.index') }}">
                        <i class="ni ni-tv-2 text-primary"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('mentor.mentees.index') ? 'active' : '' }}" href="{{ route('mentor.mentees.index') }}">
                        <i class="ni ni-single-02 text-info"></i>
                        <span class="nav-link-text">Daftar Mentee</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('mentor.matchmaking.*') ? 'active' : '' }}" href="{{ route('mentor.matchmaking.index') }}">
                        <i class="fas fa-search-location text-primary"></i>
                        <span class="nav-link-text">Job Board (Matchmaking)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('mentor.sessions.index') ? 'active' : '' }}" href="{{ route('mentor.sessions.index') }}">
                        <i class="ni ni-calendar-grid-58 text-warning"></i>
                        <span class="nav-link-text">Kelola Jadwal</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('mentor.reviews.index') ? 'active' : '' }}" href="{{ route('mentor.reviews.index') }}">
                        <i class="ni ni-chat-round text-success"></i>
                        <span class="nav-link-text">Ulasan & Rating</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>