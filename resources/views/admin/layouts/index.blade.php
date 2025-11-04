<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'SIAKAD STIKES DIAN HUSADA')</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('') }}logo.png" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts and icons -->
    <script src="{{ asset('') }}template/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('') }}template/assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('') }}template/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('') }}template/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="{{ asset('') }}template/assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('') }}template/assets/css/demo.css" />

    @stack('styles-custom')
</head>

<body>
    <div class="wrapper">
        @include('admin.layouts.nav')

        <div class="main-panel">
            @include('admin.layouts.header')

            <div class="container">
                @yield('content')
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <nav class="pull-left">
                        {{-- <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="http://www.themekita.com">
                                    ThemeKita
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Help </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Licenses </a>
                            </li>
                        </ul> --}}
                    </nav>
                    <div class="copyright">
                        {{ date('Y') }}, Dev <i class="fa fa-heart heart text-danger"></i> by
                        <a href="#">STIKES DIAN HUSADA</a>
                    </div>
                    <div>
                        {{-- Distributed by
                        <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>. --}}
                    </div>
                </div>
            </footer>
        </div>

        <!-- Custom template | don't include it in your project! -->
        <div class="custom-template">
            <div class="title">Settings</div>
            <div class="custom-content">
                <div class="switcher">
                    <div class="switch-block">
                        <h4>Logo Header</h4>
                        <div class="btnSwitch">
                            <button type="button" class="selected changeLogoHeaderColor" data-color="dark"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="blue"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="purple"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="light-blue"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="green"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="orange"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="red"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="white"></button>
                            <br />
                            <button type="button" class="changeLogoHeaderColor" data-color="dark2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="blue2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="purple2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="light-blue2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="green2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="orange2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="red2"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Navbar Header</h4>
                        <div class="btnSwitch">
                            <button type="button" class="changeTopBarColor" data-color="dark"></button>
                            <button type="button" class="changeTopBarColor" data-color="blue"></button>
                            <button type="button" class="changeTopBarColor" data-color="purple"></button>
                            <button type="button" class="changeTopBarColor" data-color="light-blue"></button>
                            <button type="button" class="changeTopBarColor" data-color="green"></button>
                            <button type="button" class="changeTopBarColor" data-color="orange"></button>
                            <button type="button" class="changeTopBarColor" data-color="red"></button>
                            <button type="button" class="selected changeTopBarColor" data-color="white"></button>
                            <br />
                            <button type="button" class="changeTopBarColor" data-color="dark2"></button>
                            <button type="button" class="changeTopBarColor" data-color="blue2"></button>
                            <button type="button" class="changeTopBarColor" data-color="purple2"></button>
                            <button type="button" class="changeTopBarColor" data-color="light-blue2"></button>
                            <button type="button" class="changeTopBarColor" data-color="green2"></button>
                            <button type="button" class="changeTopBarColor" data-color="orange2"></button>
                            <button type="button" class="changeTopBarColor" data-color="red2"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Sidebar</h4>
                        <div class="btnSwitch">
                            <button type="button" class="changeSideBarColor" data-color="white"></button>
                            <button type="button" class="selected changeSideBarColor" data-color="dark"></button>
                            <button type="button" class="changeSideBarColor" data-color="dark2"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom-toggle">
                <i class="icon-settings"></i>
            </div>
        </div>
        <!-- End Custom template -->
    </div>
    @stack('scripts-custom')

    <script src="{{ asset('') }}template/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('') }}template/assets/js/core/popper.min.js"></script>
    <script src="{{ asset('') }}template/assets/js/core/bootstrap.min.js"></script>


    <!-- jQuery Scrollbar -->
    <script src="{{ asset('') }}template/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="{{ asset('') }}template/assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('') }}template/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('') }}template/assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="{{ asset('') }}template/assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('') }}template/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    {{-- <script src="{{ asset('') }}template/assets/js/plugin/jsvectormap/jsvectormap.min.js"></script> --}}
    {{-- <script src="{{ asset('') }}template/assets/js/plugin/jsvectormap/world.js"></script> --}}

    <!-- Sweet Alert -->
    <script src="{{ asset('') }}template/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('') }}template/assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="{{ asset('') }}template/assets/js/setting-demo.js"></script>
    {{-- <script src="{{ asset('') }}template/assets/js/demo.js"></script> --}}

    {{-- <script>
        window.Laravel = {
            csrfToken: "{{ csrf_token() }}",
            accessToken: "{{ session('access_token') }}",
            expiresAt: {{ session('expires_at') ? session('expires_at') * 1000 : 'null' }}, // dalam milidetik
            refreshTokenUrl: "{{ route('refresh.token') }}",
        };
    </script>

    <script>
        function scheduleTokenRefresh() {
            const expiresAt = window.Laravel.expiresAt;
            if (!expiresAt) {
                console.warn("Tidak ada token expiration time ditemukan.");
                return;
            }

            const now = Date.now();
            const refreshThreshold = 5 * 60 * 1000; // 5 menit sebelum kadaluarsa
            const timeUntilRefresh = expiresAt - refreshThreshold;

            const delay = Math.max(0, timeUntilRefresh - now);

            if (delay > 0) {
                console.log(`Token akan direfresh dalam ${Math.floor(delay / 1000)} detik.`);
                setTimeout(refreshToken, delay);
            } else {
                console.log("Token hampir atau sudah kadaluarsa, refresh sekarang.");
                refreshToken();
            }
        }

        function refreshToken() {
            console.log("Merefresh token...");
            fetch(window.Laravel.refreshTokenUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.Laravel.csrfToken,
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (response.status === true) {
                        return response.json();
                    } else if (response.status === 401) {
                        // Refresh token gagal, logout
                        console.error("Refresh token gagal, logout otomatis.");
                        window.location.href = '/login';
                    } else {
                        throw new Error('Network response was not ok');
                    }
                })
                .then(data => {
                    if (data.success) {
                        console.log("Token berhasil direfresh.");
                        // Perbarui data di window.Laravel
                        window.Laravel.accessToken = data.access_token;
                        window.Laravel.expiresAt = data.expires_at * 1000; // ubah ke milidetik
                        scheduleTokenRefresh(); // Jadwalkan refresh berikutnya
                    } else {
                        console.error("Gagal refresh token:", data.message);
                        window.location.href = '/login';
                    }
                })
                .catch(error => {
                    console.error("Error saat refresh token:", error);
                    // window.location.href = '/login';
                });
        }

        // Jalankan saat halaman dimuat jika token ada
        if (window.Laravel.accessToken && window.Laravel.expiresAt) {
            scheduleTokenRefresh();
        }
    </script> --}}
</body>

</html>
