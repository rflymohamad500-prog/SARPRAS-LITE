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

    @php
    // Cek apakah sedang aktif di salah satu menu anak
    $isDataBarangActive = request()->routeIs('admin.items.*') || request()->routeIs('admin.consumables.*');
    @endphp

    <li class="nav-item {{ $isDataBarangActive ? 'active' : '' }}">
        <a class="nav-link {{ $isDataBarangActive ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseDataBarang"
            aria-expanded="{{ $isDataBarangActive ? 'true' : 'false' }}" aria-controls="collapseDataBarang">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Data Barang</span>
        </a>
        <div id="collapseDataBarang" class="collapse {{ $isDataBarangActive ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pilih Jenis:</h6>

                <a class="collapse-item {{ request()->routeIs('admin.items.*') ? 'active' : '' }}" href="{{ route('admin.items.index') }}">
                    <i class="fas fa-fw fa-box mr-2"></i> Aset Tetap
                </a>

                <a class="collapse-item {{ request()->routeIs('admin.consumables.*') ? 'active' : '' }}" href="{{ route('admin.consumables.index') }}">
                    <i class="fas fa-fw fa-recycle mr-2"></i> Habis Pakai
                </a>
            </div>
        </div>
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