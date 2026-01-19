<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sarpras Lite</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;

            /* --- BAGIAN INI YANG MENGATUR GAMBAR BACKGROUND --- */
            /* Kita pakai teknik tumpuk: Lapisan Biru Transparan + Gambar Sekolah */
            background: linear-gradient(rgba(10, 50, 100, 0.7), rgba(10, 50, 100, 0.8)),
            url("{{ asset('img/bg-sekolah.png') }}");

            background-size: cover;
            /* Agar gambar memenuhi layar */
            background-position: center;
            /* Agar fokus gambar di tengah */
            background-repeat: no-repeat;

            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            /* Putih agak transparan dikit */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            /* Bayangan lebih tebal */
            width: 100%;
            max-width: 450px;
            text-align: center;
            backdrop-filter: blur(5px);
            /* Efek blur di belakang kartu */
        }

        .login-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .login-subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 50px;
            padding: 20px 25px;
            background-color: #f0f2f5;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #4e73df;
            background-color: #fff;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            border-radius: 50px;
            background-color: #4e73df;
            border: none;
            color: white;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
        }

        .btn-login:hover {
            background-color: #2e59d9;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h3 class="login-title">Selamat Datang!</h3>
        <p class="login-subtitle">Sistem Inventaris Sarana Prasarana</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <input type="email" name="email" class="form-control" placeholder="Masukkan Alamat Email..." required autofocus>
            @error('email')
            <small class="text-danger d-block mb-3 text-left pl-3">{{ $message }}</small>
            @enderror

            <input type="password" name="password" class="form-control" placeholder="Password" required>
            @error('password')
            <small class="text-danger d-block mb-3 text-left pl-3">{{ $message }}</small>
            @enderror

            <div class="text-left mb-3 pl-2 custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                <label class="custom-control-label small text-muted" for="remember_me">Ingat Saya</label>
            </div>

            <button type="submit" class="btn btn-login">
                Masuk Aplikasi
            </button>

            <div class="mt-4">
                <a href="#" class="small text-muted">Lupa Password? Hubungi Admin.</a>
            </div>
        </form>
    </div>

</body>

</html>