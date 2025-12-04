{{-- Admin Sidebar (vertical sidenav for Argon) --}}
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('assets/img/brand/blue.png') }}" class="navbar-brand-img" alt="ScholarSeek">
            </a>
            <div class="ml-auto">
                <!-- collapse icon for small screens -->
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
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/home">
                        <i class="ni ni-tv-2 text-primary"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" 
                    href="{{ route('mentor.mentees.index') }}">
                        <i class="ni ni-single-02 text-info"></i>
                        <span class="nav-link-text">daftar mentee</span>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.documents.index') }}">
                        <i class="ni ni-archive-2 text-orange"></i>
                        <span class="nav-link-text">atur jadwal</span>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('mentor.schedule.index') ? 'active' : '' }}" href="{{ route('mentor.schedule.index') }}">
                        <i class="ni ni-calendar-grid-58 text-warning"></i> Kelola Jadwal
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.document.categories.index') }}">
                        <i class="ni ni-folder-17 text-info"></i>
                        <span class="nav-link-text">Pendapatan & Pembayaran</span>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('mentor.finance.index') ? 'active' : '' }}" href="{{ route('mentor.finance.index') }}">
                        <i class="ni ni-money-coins text-cyan"></i> Pendapatan & Pembayaran
                    </a>
                </li>
        
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="ni ni-briefcase-24 text-green"></i>
                        <span class="nav-link-text">Manage Scholarships</span>
                    </a>
                </li> --}}

                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('mentor.reviews.index') ? 'active' : '' }}" href="{{ route('mentor.reviews.index') }}">
                        <i class="ni ni-chat-round text-success"></i> Ulasan & Rating
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.scholarship.index') ? 'active' : '' }}" 
                    href="{{ route('admin.scholarship.index') }}">
                        <i class="ni ni-briefcase-24 text-success"></i>
                        <span class="nav-link-text">Ulasan & Rating</span>
                    </a>
                </li> --}}

                
                {{-- <li class="nav-item mt-3">
                    <h6 class="navbar-heading text-muted">Settings</h6>
                    <ul class="navbar-nav mb-md-3">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="ni ni-settings-gear-65"></i>
                                <span class="nav-link-text">Site Settings</span>
                            </a> 
                        </li>
                    </ul>
                </li> --}}
            </ul>
        </div>


    </div>
</nav>