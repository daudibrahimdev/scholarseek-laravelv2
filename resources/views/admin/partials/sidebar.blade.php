{{-- Tambahkan style z-index tinggi di sini --}}
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main" style="z-index: 1060 !important;">
    <div class="scrollbar-inner">
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('home') }}">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="icon icon-shape bg-gradient-danger text-white rounded-circle shadow-sm mr-2" style="width: 32px; height: 32px;">
                        <i class="ni ni-settings-gear-65" style="font-size: 0.85rem;"></i>
                    </div>
                    <div class="d-flex flex-column text-left">
                        <span class="font-weight-900 text-uppercase" style="font-size: 1.1rem; letter-spacing: 0.5px; color: #5e72e4; line-height: 1;">
                            Scholar<span style="color: #172b4d;">Seek</span>
                        </span>
                        <small class="text-danger font-weight-bold text-uppercase" style="font-size: 0.6rem; letter-spacing: 1px;">Admin Panel</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="navbar-inner">
            <div class="navbar-collapse-direct">
                <h6 class="navbar-heading p-0 text-muted px-4 small fw-bold text-uppercase mt-4">Utama</h6>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="ni ni-tv-2 text-primary"></i>
                            <span class="nav-link-text font-weight-bold text-dark">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="ni ni-single-02 text-info"></i>
                            <span class="nav-link-text text-dark">Kelola Pengguna</span>
                        </a>
                    </li>
                </ul>

                <hr class="my-3">

                <h6 class="navbar-heading p-0 text-muted px-4 small fw-bold text-uppercase">Program Beasiswa</h6>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('admin.scholarship.index') ? 'active' : '' }}" href="{{ route('admin.scholarship.index') }}">
                            <i class="ni ni-briefcase-24 text-success"></i>
                            <span class="nav-link-text text-dark">Daftar Beasiswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('admin.scholarship.categories.*') ? 'active' : '' }}" href="{{ route('admin.scholarship.categories.index') }}">
                            <i class="ni ni-tag text-purple"></i>
                            <span class="nav-link-text text-dark">Kategori Beasiswa</span>
                        </a>
                    </li>
                </ul>

                <hr class="my-3">

                <h6 class="navbar-heading p-0 text-muted px-4 small fw-bold text-uppercase">Pusat Bantuan</h6>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('admin.documents.index') ? 'active' : '' }}" href="{{ route('admin.documents.index') }}">
                            <i class="ni ni-archive-2 text-orange"></i>
                            <span class="nav-link-text text-dark">Data Dokumen</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('admin.document.categories.*') ? 'active' : '' }}" href="{{ route('admin.document.categories.index') }}">
                            <i class="ni ni-folder-17 text-info"></i>
                            <span class="nav-link-text text-dark">Kategori Dokumen</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>