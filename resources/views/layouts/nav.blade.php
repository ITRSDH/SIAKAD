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
                    <a href="{{ route('profile') }}">
                        <i class="fas fa-home"></i>
                        <p>Profile</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Dashboard</h4>
                </li>
                @canApi('dashboard.admin')
                <li class="nav-item">
                    <a href="{{ route('dashboard.administrator') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard ADMIN</p>
                    </a>
                </li>
                @endcanApi

                @canApi('dashboard.baak')
                <li class="nav-item">
                    <a href="{{ route('dashboard.baak') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard BAAK</p>
                    </a>
                </li>
                @endcanApi

                @canApi('dashboard.kaprodi')
                <li class="nav-item">
                    <a href="{{ route('dashboard.kaprodi') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard KAPRODI</p>
                    </a>
                </li>
                @endcanApi

                @canApi('dashboard.dosen_pa')
                <li class="nav-item">
                    <a href="{{ route('dashboard.dosen_pa') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard DOSEN PA</p>
                    </a>
                </li>
                @endcanApi

                @canApi('dashboard.dosen_pengampu')
                <li class="nav-item">
                    <a href="{{ route('dashboard.dosen_pengampu') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard DOSEN PENGAAMPU</p>
                    </a>
                </li>
                @endcanApi

                @canApi('dashboard.mahasiswa')
                <li class="nav-item">
                    <a href="{{ route('dashboard.mahasiswa') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard MAHASISWA</p>
                    </a>
                </li>
                @endcanApi

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Website Kampus</h4>
                </li>
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#masterwebsite" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Menu Landing</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="masterwebsite">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('pengumuman.index') }}">
                                    <span class="sub-item">Pengumuman</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('prestasi.index') }}">
                                    <span class="sub-item">Prestasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('beasiswa.index') }}">
                                    <span class="sub-item">Beasiswa</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('berita.index') }}">
                                    <span class="sub-item">Berita</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('galeri.index') }}">
                                    <span class="sub-item">Galeri</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('faq.index') }}">
                                    <span class="sub-item">Faq</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('landing-content.index') }}">
                                    <span class="sub-item">Landing Content</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('ormawa.index') }}">
                                    <span class="sub-item">Ormawa</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile-kampus.index') }}">
                                    <span class="sub-item">Profile Kampus</span>
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
                    <a data-bs-toggle="collapse" href="#mastersiakad" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Master Data</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="mastersiakad">
                        <ul class="nav nav-collapse">

                            <!-- REFERENSI -->
                            <li>
                                <a data-bs-toggle="collapse" href="#subnavRef">
                                    <span class="sub-item">Referensi</span>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="subnavRef">
                                    <ul class="nav nav-collapse subnav">
                                        <li><a href="{{ route('jenjang-pendidikan.index') }}"><span
                                                    class="sub-item">Jenjang Pendidikan</span></a></li>
                                        <li><a href="{{ route('prodi.index') }}"><span class="sub-item">Program
                                                    Studi</span></a></li>
                                        <li><a href="{{ route('tahun-akademik.index') }}"><span
                                                    class="sub-item">Tahun
                                                    Akademik</span></a></li>
                                        <li><a href="{{ route('kurikulum.index') }}"><span
                                                    class="sub-item">Kurikulum</span></a></li>
                                        <li><a href="{{ route('mata-kuliah.index') }}"><span class="sub-item">Mata
                                                    Kuliah</span></a></li>
                                        <li><a href="{{ route('jenis-kelas.index') }}"><span class="sub-item">Jenis
                                                    Kelas</span></a></li>
                                        <li><a href="{{ route('jenis-pembayaran.index') }}"><span
                                                    class="sub-item">Jenis
                                                    Pembayaran</span></a></li>
                                        <li><a href="{{ route('ruang.index') }}"><span class="sub-item">Ruang
                                                    Kuliah</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <!-- SETTING AKADEMIK -->
                            {{-- <li>
                                <a data-bs-toggle="collapse" href="#subnavSet">
                                    <span class="sub-item">Setting Akademik</span>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="subnavSet">
                                    <ul class="nav nav-collapse subnav">
                                        <li><a href="{{ route('tahun-akademik.index') }}"><span class="sub-item">Tahun
                                                    Akademik</span></a></li>
                                        <li><a href="{{ route('semester.index') }}"><span
                                                    class="sub-item">Semester</span></a></li>
                                        <li><a href="{{ route('kurikulum.index') }}"><span
                                                    class="sub-item">Kurikulum</span></a></li>
                                        <li><a href="{{ route('matakuliah.index') }}"><span class="sub-item">Mata
                                                    Kuliah</span></a></li>
                                        <li><a href="{{ route('kelas-pararel.index') }}"><span class="sub-item">Kelas
                                                    Pararel</span></a></li>
                                        <li><a href="#"><span class="sub-item">Kelas Mata Kuliah</span></a></li>
                                        <li><a href="#"><span class="sub-item">Jadwal Kuliah</span></a></li>
                                        <li><a href="#"><span class="sub-item">Presensi Mahasiswa</span></a>
                                        </li>
                                        <li><a href="#"><span class="sub-item">Nilai Mahasiswa</span></a></li>
                                        <li><a href="#"><span class="sub-item">Kartu Rencana Studi
                                                    (KRS)</span></a></li>
                                        <li><a href="#"><span class="sub-item">Kartu Hasil Studi
                                                    (KHS)</span></a>
                                        </li>
                                        <li><a href="#"><span class="sub-item">Wali Kelas</span></a></li>
                                        <li><a href="#"><span class="sub-item">Status Akademik</span></a></li>
                                        <li><a href="#"><span class="sub-item">Pembayaran Mahasiswa</span></a>
                                        </li>
                                        <li><a href="#"><span class="sub-item">Berkas Mahasiswa</span></a></li>
                                        <li><a href="#"><span class="sub-item">Alumni</span></a></li>
                                    </ul>
                                </div>
                            </li> --}}

                            {{-- <li class="nav-item">
                                <a href="#">
                                    <i class="fas fa-home"></i>
                                    <p>Data Dosen</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#">
                                    <i class="fas fa-home"></i>
                                    <p>Data Mahasiswa</p>
                                </a>
                            </li> --}}

                        </ul>
                    </div>
                </li>

                @canAnyApi(
                'pengguna.setting.users.index',
                'pengguna.setting.roles.index',
                'pengguna.setting.permissions.index'
                )

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Pengguna</h4>
                </li>

                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#masterpengguna" class="collapsed">
                        <i class="fas fa-home"></i>
                        <p>Setting</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse" id="masterpengguna">
                        <ul class="nav nav-collapse">

                            @canApi('pengguna.setting.users.index')
                            <li><a href="{{ route('users.index') }}"><span class="sub-item">User</span></a></li>
                            @endcanApi

                            @canApi('pengguna.setting.roles.index')
                            <li><a href="{{ route('roles.index') }}"><span class="sub-item">Role</span></a></li>
                            @endcanApi
                            @canApi('pengguna.setting.permissions.index')
                            <li><a href="{{ route('permissions.index') }}"><span
                                        class="sub-item">Permission</span></a>
                            </li>
                            @endcanApi
                        </ul>
                    </div>
                </li>

                @endcanAnyApi


                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Sign-Out</h4>
                </li>
                <li class="nav-item">
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form-nav" method="POST" action="{{ route('logout') }}" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>

</div>
<!-- End Sidebar -->
