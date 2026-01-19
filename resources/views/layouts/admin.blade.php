<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="SARPRAS LITE">
    <meta name="author" content="">

    <title>SARPRAS LITE - @yield('title', 'Dashboard')</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

    @stack('styles')

    <style>
        /* Transisi Global */
        body,
        div,
        nav,
        aside,
        footer,
        table,
        tr,
        td,
        th,
        span,
        a,
        i,
        button,
        input,
        select,
        textarea {
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        }

        /* --- MODE GELAP AKTIF --- */
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }

        body.dark-mode #wrapper #content-wrapper {
            background-color: #121212 !important;
        }

        body.dark-mode #content {
            background-color: #121212 !important;
        }

        /* Navbar & Sidebar */
        body.dark-mode .navbar {
            background-color: #1e1e1e !important;
            border-bottom: 1px solid #333 !important;
        }

        body.dark-mode .navbar .nav-link .text-gray-600 {
            color: #e0e0e0 !important;
        }

        body.dark-mode .topbar-divider {
            border-right: 1px solid #444 !important;
        }

        body.dark-mode ul.sidebar {
            background-color: #000000 !important;
            background-image: none !important;
            border-right: 1px solid #333;
        }

        body.dark-mode .sidebar-heading {
            color: #888 !important;
        }

        body.dark-mode .nav-item .nav-link {
            color: #ccc !important;
        }

        body.dark-mode .nav-item.active .nav-link {
            color: #fff !important;
            font-weight: bold;
        }

        body.dark-mode .sidebar-divider {
            border-top: 1px solid #333 !important;
        }

        body.dark-mode footer.sticky-footer {
            background-color: #1e1e1e !important;
            color: #aaa !important;
            border-top: 1px solid #333;
        }

        /* Card & Components */
        body.dark-mode .card {
            background-color: #1e1e1e !important;
            border: 1px solid #333 !important;
            color: #e0e0e0 !important;
        }

        body.dark-mode .card-header {
            background-color: #252525 !important;
            border-bottom: 1px solid #333 !important;
            color: #fff !important;
        }

        body.dark-mode .card-header h6 {
            color: #fff !important;
        }

        body.dark-mode .form-control,
        body.dark-mode .custom-select {
            background-color: #2c2c2c !important;
            border: 1px solid #444 !important;
            color: #fff !important;
        }

        body.dark-mode .form-control:focus {
            background-color: #333 !important;
            color: #fff !important;
            border-color: #4e73df !important;
        }

        /* Table */
        body.dark-mode .table {
            color: #e0e0e0 !important;
        }

        body.dark-mode .table thead th {
            background-color: #2c2c2c !important;
            border-color: #444 !important;
            color: #fff !important;
        }

        body.dark-mode .table td,
        body.dark-mode .table th {
            border-color: #333 !important;
        }

        /* Dropdown & Modal */
        body.dark-mode .dropdown-menu,
        body.dark-mode .modal-content {
            background-color: #1e1e1e !important;
            border: 1px solid #444 !important;
            color: #e0e0e0 !important;
        }

        body.dark-mode .dropdown-item {
            color: #e0e0e0 !important;
        }

        body.dark-mode .dropdown-item:hover {
            background-color: #333 !important;
            color: #fff !important;
        }

        body.dark-mode .modal-header,
        body.dark-mode .modal-footer {
            border-color: #333 !important;
        }

        body.dark-mode .close {
            color: #fff !important;
            text-shadow: none;
        }

        /* Utility Colors */
        body.dark-mode .text-gray-800,
        body.dark-mode .text-dark,
        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode h4,
        body.dark-mode h5,
        body.dark-mode h6 {
            color: #f1f1f1 !important;
        }

        body.dark-mode .text-gray-600,
        body.dark-mode .text-muted {
            color: #aaa !important;
        }

        body.dark-mode .list-group-item {
            background-color: #1e1e1e !important;
            border-color: #333 !important;
        }

        /* Tombol Dark Mode di Navbar */
        .btn-theme-toggle {
            color: #4e73df;
            background-color: #f8f9fa;
            border: 1px solid #e3e6f0;
        }

        body.dark-mode .btn-theme-toggle {
            color: #f6c23e;
            /* Kuning Matahari */
            background-color: #2c2c2c;
            border-color: #444;
        }
    </style>
</head>

<body id="page-top">

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <div id="wrapper">

        @include('layouts.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item d-flex align-items-center">
                            <button onclick="toggleDarkModeGlobal()" class="btn btn-sm btn-circle btn-theme-toggle shadow-sm mr-3" title="Ganti Tema">
                                <i class="fas fa-moon" id="globalThemeIcon"></i>
                            </button>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                @if(Auth::user()->avatar)
                                <img class="img-profile rounded-circle" src="{{ asset('storage/' . Auth::user()->avatar) }}" style="object-fit: cover;">
                                @else
                                <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random">
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil Saya
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    @if (session('success'))
                    <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger border-left-danger" role="alert">
                        <ul class="pl-4 my-2">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; SARPRAS LITE 2026</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin ingin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" di bawah jika Anda ingin mengakhiri sesi.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>
    @stack('scripts')

    <script>
        // Cek Ikon saat load
        const icon = document.getElementById('globalThemeIcon');
        if (localStorage.getItem('theme') === 'dark') {
            if (icon) icon.classList.replace('fa-moon', 'fa-sun');
        }

        function toggleDarkModeGlobal() {
            document.body.classList.toggle('dark-mode');
            const icon = document.getElementById('globalThemeIcon');

            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                if (icon) icon.classList.replace('fa-moon', 'fa-sun');
            } else {
                localStorage.setItem('theme', 'light');
                if (icon) icon.classList.replace('fa-sun', 'fa-moon');
            }
        }
    </script>

</body>

</html>