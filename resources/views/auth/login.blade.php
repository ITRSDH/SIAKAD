<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('') }}logo.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css    ">
    <link rel="stylesheet" href="{{ asset('') }}assets/css/styles.min.css" />
    <title>Login</title>

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .logo-image {
            border-radius: 50%;
            max-width: 100%;
            height: auto;
        }

        .logo-text {
            color: #50b498;
            margin-left: 10px;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .auth-container {
            background-color: #f7f9fb;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            /* Padding tetap sama */
        }

        .auth-container .form-control {
            border-radius: 50px;
            padding: 12px;
            font-size: 14px;
        }

        .auth-container .btn-success {
            background: #50b498;
            border-radius: 50px;
            padding: 10px;
            font-size: 16px;
        }

        .auth-container .auth-title {
            font-weight: bold;
            font-size: 24px;
            color: #333;
        }

        .auth-container .auth-subtitle {
            font-size: 14px;
            color: #6c757d;
        }

        .gradient-background {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .form-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        /* Gaya untuk logo di dalam form - Sedikit disesuaikan */
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            /* Pusatkan logo dan teks */
            margin-bottom: 15px;
            /* Sedikit dikurangi agar lebih dekat dengan subtitle */
            padding-top: 10px;
            /* Tambahkan sedikit ruang dari atas form */
        }

        /* Gaya untuk teks versi di bawah */
        .version-text {
            display: block;
            /* Agar span bisa rata tengah */
            text-align: center;
            margin-top: 20px;
            /* Jarak dari tombol ke teks versi */
            font-size: 15px;
            /* Ukuran font lebih kecil */
            color: #50b498;
            /* Warna agak pudar */
            font-weight: bold;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>
    <div class="gradient-background">
        <div class="container">
            <div class="row justify-content-center align-items-center h-100">
                <!-- Kolom untuk ilustrasi (tetap sama) -->
                <div
                    class="col-lg-7 col-xl-8 text-center d-none d-md-flex justify-content-center align-items-center h-100">
                    <img src="{{ asset('') }}ilustrasi.png" alt="modernize-img" class="img-fluid">
                </div>

                <!-- Kolom untuk form login -->
                <div class="col-lg-5 col-xl-4">
                    <div class="auth-container">
                        <!-- Logo ditempatkan DI ATAS form, di dalam container -->
                        <div class="logo-container">
                            <img src="{{ asset('') }}logo.png" width="45" alt="Logo" class="logo-image">
                            <span class="logo-text">SIAKAD STIKES DIAN HUSADA</span>
                        </div>

                        {{-- <h2 class="auth-title text-center">SIAKAD STIKES DIAN HUSADA</h2> --}} <!-- Masih dikomentari -->
                        <p class="auth-subtitle text-center">Silakan masukkan kredensial Anda.</p>

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4 position-relative">
                                <i class="ti ti-user fs-6 form-icon"></i>
                                <input type="email" class="form-control" id="email" name="email" required
                                    placeholder="Email Address">
                            </div>
                            <div class="mb-4 position-relative">
                                <i class="ti ti-lock fs-6 form-icon"></i>
                                <input type="password" class="form-control" id="password" name="password" required
                                    placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-success w-100">Sign In</button>
                        </form>
                        <span class="version-text">S-DH Ver 1.0.Beta</span> <!-- Gunakan class baru -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js    "></script>
</body>

</html>
