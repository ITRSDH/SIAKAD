<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo text-section">
                {{-- <img src="{{ asset('') }}template/assets/img/kaiadmin/logo_light.svg" alt="navbar brand"
                    class="navbar-brand" height="20" /> --}}
                <span style="color: #ffffff; text-shadow: 0 0 2px white;">STIKES DIAN HUSADA</span>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Website Kampus</h4>
                </li>
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#masterwebsite" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Menu Website</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="masterwebsite">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="#">
                                    <span class="sub-item">Website</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">PMB</h4>
                </li>
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#masterpmb" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Menu PBM</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="masterpmb">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="#">
                                    <span class="sub-item">Periode Pendaftaran</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Jalur Pendaftaran</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Biaya Pendaftaran</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Syarat Pendaftaran</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Gelombang</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Jadwal PMB</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Calon Mahasiswa</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">SIAKAD</h4>
                </li>
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#submenu1">
                        <i class="fas fa-home"></i>
                        <p>Menu Akademik</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="submenu1">
                        <ul class="nav nav-collapse">
                            <li>
                                <a data-bs-toggle="collapse" href="#subnav1">
                                    <span class="sub-item">Master Akademik</span>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="subnav1">
                                    <ul class="nav nav-collapse subnav">
                                        <li>
                                            <a href="{{ route('jenjang-pendidikan.index') }}">
                                                <span class="sub-item">Jenjang Pendidikan</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('prodi.index') }}">
                                                <span class="sub-item">Program Studi</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('tahun-akademik.index') }}">
                                                <span class="sub-item">Tahun Akademik</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('semester.index') }}">
                                                <span class="sub-item">Semester</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('kurikulum.index') }}">
                                                <span class="sub-item">Kurikulum</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('matakuliah.index') }}">
                                                <span class="sub-item">Mata Kuliah</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('jenis-kelas.index') }}">
                                                <span class="sub-item">Jenis Kelas</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('kelas-pararel.index') }}">
                                                <span class="sub-item">Kelas Pararel</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('ruang.index') }}">
                                                <span class="sub-item">Ruang Kuliah</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('jenis-pembayaran.index') }}">
                                                <span class="sub-item">Jenis Pembayaran</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a data-bs-toggle="collapse" href="#subnav2">
                                    <span class="sub-item">Proses Akademik</span>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="subnav2">
                                    <ul class="nav nav-collapse subnav">
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Kelas Mata Kuliah</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Jadwal Kuliah</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Presensi Mahasiswa</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Nilai Mahasiswa</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Kartu Rencana Studi (KRS)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Kartu Hasil Studi (KHS)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Wali Kelas</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Status Akademik</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Pembayaran Mahasiswa</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Berkas Mahasiswa</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Alumni</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a data-bs-toggle="collapse" href="#subnav3">
                                    <span class="sub-item">Laporan/Hasil Akademik</span>
                                    <span class="caret"></span>
                                </a>
                            </li>
                            <div class="collapse" id="subnav3">
                                <ul class="nav nav-collapse subnav">
                                    <li>
                                        <a href="#">
                                            <span class="sub-item">Level 2</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Pengguna</h4>
                </li>
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#masterpengguna" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Menu Pengguna</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="masterpengguna">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="#">
                                    <span class="sub-item">Staff</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Dosen</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Mahasiswa</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-bs-toggle="collapse" href="#submenu2">
                                    <i class="fas fa-bars"></i>
                                    <p>Setting User</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="submenu2">
                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a data-bs-toggle="collapse" href="#subnav1">
                                                <span class="sub-item">Management User</span>
                                                <span class="caret"></span>
                                            </a>
                                            <div class="collapse" id="subnav1">
                                                <ul class="nav nav-collapse subnav">
                                                    <li>
                                                        <a href="#">
                                                            <span class="sub-item">User</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <span class="sub-item">Role</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <span class="sub-item">Permission</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Sign-Out</h4>
                </li>
                <li class="nav-item">
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
