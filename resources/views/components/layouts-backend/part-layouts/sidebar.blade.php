<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('dashboard') }}" class="brand-link">
        <img src="{{ $logoUrl }}" alt="Logo"class="brand-image">
        <span class="brand-text font-weight-light">{{ $appName }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://diskominfo.wonosobokab.go.id/uploads/kominfo.png" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Hi, {{ Auth::user()->name ?? 'Guest' }}</a>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm btn-block">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="true">
                <!-- Dashboard Menu -->
                <li class="nav-item">
                    <a href="{{ url('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('category') }}" class="nav-link {{ request()->is('category') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>Category</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('article') }}" class="nav-link {{ request()->is('article') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>Article</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('pages') }}" class="nav-link {{ request()->is('pages') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Page</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('menu') }}" class="nav-link {{ request()->is('menu') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bars"></i>
                        <p>Menu Manage</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('file-panduan') }}"
                        class="nav-link {{ request()->is('file-panduan') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>File Panduan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('setting-web') }}"
                        class="nav-link {{ request()->is('setting-web') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Setting Web</p>
                    </a>
                </li>



            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
