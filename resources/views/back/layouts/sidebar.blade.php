<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                @if(Auth::guard('admin')->check())
                <!-- Admin Sidebar -->
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Data Management</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Master Data
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.kelas.index') }}">Kelas</a>
                        <a class="nav-link" href="{{ route('admin.mapel.index') }}">Mata Pelajaran</a>
                        <a class="nav-link" href="{{ route('admin.siswa.index') }}">Siswa</a>
                    </nav>
                </div>
                
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Pembelajaran
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.tugas.index') }}">Tugas</a>
                        <a class="nav-link" href="{{ route('admin.nilai.index') }}">Nilai</a>
                    </nav>
                </div>
                
                <!-- Authentication Links -->
                <div class="sb-sidenav-menu-heading">Account</div>
                <a class="nav-link" href="{{ route('admin.logout') }}"
                   onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                    Logout
                </a>
                <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                @elseif(Auth::guard('siswa')->check())
                <!-- Siswa Sidebar -->
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="{{ route('siswa.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                
                <div class="sb-sidenav-menu-heading">Pembelajaran</div>
                <a class="nav-link" href="{{ route('siswa.tugas.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tasks"></i></div>
                    Tugas
                </a>
                
                <!-- Authentication Links -->
                <div class="sb-sidenav-menu-heading">Account</div>
                <a class="nav-link" href="{{ route('siswa.logout') }}"
                   onclick="event.preventDefault(); document.getElementById('siswa-logout-form').submit();">
                    <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                    Logout
                </a>
                <form id="siswa-logout-form" action="{{ route('siswa.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                @endif
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            @if(Auth::guard('admin')->check())
                Admin: {{ Auth::guard('admin')->user()->name }}
            @elseif(Auth::guard('siswa')->check())
                Siswa: {{ Auth::guard('siswa')->user()->nama }}
            @else
                Guest
            @endif
        </div>
    </nav>
</div>