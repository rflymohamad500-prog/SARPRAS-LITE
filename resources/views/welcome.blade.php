<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarpras Lite - Sistem Inventaris Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            /* Abu kebiruan muda */
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            padding: 20px 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #4e73df !important;
        }

        /* Hero Section */
        .hero-section {
            padding: 100px 0;
            min-height: 90vh;
            display: flex;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 3rem;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero-text p {
            font-size: 1.1rem;
            color: #7f8c8d;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-primary-custom {
            background-color: #4e73df;
            border: none;
            padding: 12px 35px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.4);
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            background-color: #2e59d9;
            transform: translateY(-3px);
            color: white;
            text-decoration: none;
        }

        /* Ilustrasi (Mengambil dari unDraw agar mirip referensi) */
        .hero-img img {
            width: 100%;
            max-width: 600px;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translatey(0px);
            }

            50% {
                transform: translatey(-20px);
            }

            100% {
                transform: translatey(0px);
            }
        }

        /* Shape Background */
        .bg-shape {
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: #eef2ff;
            z-index: -1;
            border-bottom-left-radius: 100% 50%;
        }
    </style>
</head>

<body>

    <div class="bg-shape d-none d-lg-block"></div>

    <nav class="navbar navbar-expand-lg navbar-light container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-boxes"></i> SARPRAS LITE
        </a>
        <div class="ml-auto">
            @if (Route::has('login'))
            @auth
            <a href="{{ url('/dashboard') }}" class="font-weight-bold text-dark">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">Masuk</a>
            @endauth
            @endif
        </div>
    </nav>

    <section class="hero-section container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-text">
                <span class="badge badge-primary px-3 py-2 mb-3 rounded-pill" style="background: #e6f0ff; color: #4e73df;">Versi Terbaru 2026</span>
                <h1>Kelola Aset Sekolah <br> Jadi Lebih Mudah</h1>
                <p>Sistem Informasi Sarana dan Prasarana (SARPRAS) untuk pencatatan, peminjaman, dan pelaporan inventaris sekolah yang modern dan efisien.</p>

                <a href="{{ route('login') }}" class="btn btn-primary-custom">
                    Mulai Sekarang <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="col-lg-6 hero-img text-center">

                <img src="{{ asset('img/bg-sekolah.png') }}"
                    alt="Ilustrasi Sarpras"
                    style="width: 100%; max-width: 550px; animation: float 6s ease-in-out infinite;">

            </div>
        </div>
    </section>

</body>

</html>