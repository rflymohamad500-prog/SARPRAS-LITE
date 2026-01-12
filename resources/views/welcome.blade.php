<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Sarpras - SMKN 4 Gorontalo</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <style>
        /* CSS Khusus untuk Background Gambar Full Layar */
        body {
            /* --- BAGIAN INI YANG DIUBAH --- */
            /* Kita gunakan {{ asset('...') }} untuk memanggil file dari folder public */
            /* Pastikan nama filenya sama persis dengan yang kamu simpan (misal: bg-sekolah.jpg) */
            background: url("{{ asset('img/bg-sekolah.png') }}");

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            /* Tinggi layar penuh */
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Lapisan Gelap di atas gambar agar teks terbaca */
        /* Jika fotomu terlalu gelap, ubah angka 0.6 menjadi lebih kecil (misal 0.4) */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            /* Hitam transparan 60% */
            z-index: 1;
        }

        /* Kontainer Konten */
        .content-wrapper {
            position: relative;
            z-index: 2;
            /* Agar berada di atas lapisan gelap */
            text-align: center;
            color: white;
            max-width: 700px;
            padding: 20px;
        }

        .logo-img {
            width: 120px;
            margin-bottom: 20px;
            background: white;
            border-radius: 50%;
            padding: 10px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }

        .main-title {
            font-size: 3rem;
            font-weight: 800;
            text-transform: uppercase;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin-bottom: 10px;
        }

        .sub-title {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 40px;
            color: #f8f9fa;
        }

        .btn-welcome {
            padding: 15px 40px;
            font-size: 1.2rem;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-welcome:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>

<body>

    <div class="overlay"></div>

    <div class="content-wrapper">

        @if(file_exists(public_path('img/logo.png')))
        <img src="{{ asset('img/logo.png') }}" class="logo-img" alt="Logo">
        @else
        <div class="logo-img d-inline-block text-primary">
            <i class="fas fa-school fa-4x"></i>
        </div>
        @endif

        <h1 class="main-title">Sistem Informasi<br>Sarana Prasarana</h1>
        <h3 class="sub-title">SMK Negeri 4 Gorontalo</h3>

        @if (Route::has('login'))
        @auth
        <a href="{{ url('/dashboard') }}" class="btn btn-success btn-welcome">
            <i class="fas fa-tachometer-alt mr-2"></i> Ke Dashboard
        </a>
        @else
        <a href="{{ route('login') }}" class="btn btn-primary btn-welcome">
            <i class="fas fa-sign-in-alt mr-2"></i> Masuk Aplikasi
        </a>
        @endauth
        @endif

        <div class="mt-5 text-white-50 small">
            &copy; {{ date('Y') }} Tim IT Sekolah. All Rights Reserved.
        </div>
    </div>

</body>

</html>