<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('logo.png') }}" width="50" alt="Logo Sekolah">
        </div>
        <div class="sidebar-brand-text mx-2">
            SARPRAS <sup>LITE</sup>
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->is('admin/dashboard') || request()->is('petugas/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('petugas.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    @if(Auth::user()->role == 'admin')
    <div class="sidebar-heading">
        Master Data
    </div>

    <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.categories.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>Kategori</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.rooms.index') }}">
            <i class="fas fa-fw fa-door-open"></i>
            <span>Ruangan & Lokasi</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.items.index') }}">
            <i class="fas fa-fw fa-box"></i>
            <span>Aset Tetap</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.consumables.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.consumables.index') }}">
            <i class="fas fa-fw fa-recycle"></i>
            <span>Barang Habis Pakai</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('admin/labels*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.labels.index') }}">
            <i class="fas fa-fw fa-print"></i>
            <span>Cetak Label Aset</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Manajemen User</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    @endif

    <div class="sidebar-heading">
        Transaksi & Laporan
    </div>

    <li class="nav-item {{ request()->routeIs('admin.borrowings*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.borrowings.index') }}">
            <i class="fas fa-fw fa-hand-holding"></i>
            <span>Peminjaman Aset</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.transactions.index') }}">
            <i class="fas fa-fw fa-exchange-alt"></i>
            <span>Riwayat Stok</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.index') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Pusat Laporan</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>