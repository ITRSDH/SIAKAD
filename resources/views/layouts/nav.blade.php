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
                <li class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <a href="{{ route('profile') }}">
                        <i class="fas fa-home"></i>
                        <p>Profile</p>
                    </a>
                </li>

                {{-- @canAnyApi(
                'dashboard.admin',
                'dashboard.baak',
                'dashboard.kaprodi',
                'dashboard.dosen_pa',
                'dashboard.dosen_pengampu',
                'dashboard.mahasiswa'
                )
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Dashboard</h4>
                </li>
                @canApi('dashboard.admin')
                <li class="nav-item {{ request()->routeIs('dashboard.administrator') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.administrator') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard ADMIN</p>
                    </a>
                </li>
                @endcanApi

                @canApi('dashboard.baak')
                <li class="nav-item {{ request()->routeIs('dashboard.baak') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.baak') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard BAAK</p>
                    </a>
                </li>
                @endcanApi

                @canApi('dashboard.kaprodi')
                <li class="nav-item {{ request()->routeIs('dashboard.kaprodi') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.kaprodi') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard KAPRODI</p>
                    </a>
                </li>
                @endcanApi

                @canApi('dashboard.dosen_pa')
                <li class="nav-item {{ request()->routeIs('dashboard.dosen_pa') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.dosen_pa') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard DOSEN PA</p>
                    </a>
                </li>
                @endcanApi

                @canApi('dashboard.dosen_pengampu')
                <li class="nav-item {{ request()->routeIs('dashboard.dosen_pengampu') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.dosen_pengampu') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard DOSEN PENGAAMPU</p>
                    </a>
                </li>
                @endcanApi

                @canApi('dashboard.mahasiswa')
                <li class="nav-item {{ request()->routeIs('dashboard.mahasiswa') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.mahasiswa') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard MAHASISWA</p>
                    </a>
                </li>
                @endcanApi
                @endcanAnyApi --}}


                @canAnyApi(
                'websitekampus.landing.pengumuman.index',
                'websitekampus.landing.prestasi.index',
                'websitekampus.landing.beasiswa.index',
                'websitekampus.landing.berita.index',
                'websitekampus.landing.galeri.index',
                'websitekampus.landing.faq.index',
                'websitekampus.landing.landing-content.index',
                'websitekampus.landing.ormawa.index',
                'websitekampus.landing.profile-kampus.index',
                )
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Website Kampus</h4>
                </li>
                <li
                    class="nav-item {{ request()->routeIs([
                        'pengumuman.*',
                        'prestasi.*',
                        'beasiswa.*',
                        'berita.*',
                        'galeri.*',
                        'faq.*',
                        'landing-content.*',
                        'ormawa.*',
                        'profile-kampus.*',
                    ])
                        ? 'active'
                        : '' }}">
                    <a data-bs-toggle="collapse" href="#masterwebsite"
                        class="{{ request()->routeIs([
                            'pengumuman.*',
                            'prestasi.*',
                            'beasiswa.*',
                            'berita.*',
                            'galeri.*',
                            'faq.*',
                            'landing-content.*',
                            'ormawa.*',
                            'profile-kampus.*',
                        ])
                            ? ''
                            : 'collapsed' }}"
                        aria-expanded="{{ request()->routeIs([
                            'pengumuman.*',
                            'prestasi.*',
                            'beasiswa.*',
                            'berita.*',
                            'galeri.*',
                            'faq.*',
                            'landing-content.*',
                            'ormawa.*',
                            'profile-kampus.*',
                        ])
                            ? 'true'
                            : 'false' }}">
                        <i class="fas fa-home"></i>
                        <p>Menu Landing</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs([
                        'pengumuman.*',
                        'prestasi.*',
                        'beasiswa.*',
                        'berita.*',
                        'galeri.*',
                        'faq.*',
                        'landing-content.*',
                        'ormawa.*',
                        'profile-kampus.*',
                    ])
                        ? 'show'
                        : '' }}"
                        id="masterwebsite">
                        <ul class="nav nav-collapse">
                            @canApi('websitekampus.landing.pengumuman.index')
                            <li class="{{ request()->routeIs('pengumuman.*') ? 'active' : '' }}">
                                <a href="{{ route('pengumuman.index') }}">
                                    <span class="sub-item">Pengumuman</span>
                                </a>
                            </li>
                            @endcanApi
                            @canApi('websitekampus.landing.prestasi.index')
                            <li class="{{ request()->routeIs('prestasi.*') ? 'active' : '' }}">
                                <a href="{{ route('prestasi.index') }}">
                                    <span class="sub-item">Prestasi</span>
                                </a>
                            </li>
                            @endcanApi
                            @canApi('websitekampus.landing.beasiswa.index')
                            <li class="{{ request()->routeIs('beasiswa.*') ? 'active' : '' }}">
                                <a href="{{ route('beasiswa.index') }}">
                                    <span class="sub-item">Beasiswa</span>
                                </a>
                            </li>
                            @endcanApi
                            @canApi('websitekampus.landing.berita.index')
                            <li class="{{ request()->routeIs('berita.*') ? 'active' : '' }}">
                                <a href="{{ route('berita.index') }}">
                                    <span class="sub-item">Berita</span>
                                </a>
                            </li>
                            @endcanApi
                            @canApi('websitekampus.landing.galeri.index')
                            <li class="{{ request()->routeIs('galeri.*') ? 'active' : '' }}">
                                <a href="{{ route('galeri.index') }}">
                                    <span class="sub-item">Galeri</span>
                                </a>
                            </li>
                            @endcanApi
                            @canApi('websitekampus.landing.faq.index')
                            <li class="{{ request()->routeIs('faq.*') ? 'active' : '' }}">
                                <a href="{{ route('faq.index') }}">
                                    <span class="sub-item">Faq</span>
                                </a>
                            </li>
                            @endcanApi
                            @canApi('websitekampus.landing.landing-content.index')
                            <li class="{{ request()->routeIs('landing-content.*') ? 'active' : '' }}">
                                <a href="{{ route('landing-content.index') }}">
                                    <span class="sub-item">Landing Content</span>
                                </a>
                            </li>
                            @endcanApi
                            @canApi('websitekampus.landing.ormawa.index')
                            <li class="{{ request()->routeIs('ormawa.*') ? 'active' : '' }}">
                                <a href="{{ route('ormawa.index') }}">
                                    <span class="sub-item">Ormawa</span>
                                </a>
                            </li>
                            @endcanApi
                            @canApi('websitekampus.landing.profile-kampus.index')
                            <li class="{{ request()->routeIs('profile-kampus.*') ? 'active' : '' }}">
                                <a href="{{ route('profile-kampus.index') }}">
                                    <span class="sub-item">Profile Kampus</span>
                                </a>
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
                    <h4 class="text-section">Mahasiswa</h4>
                </li>
                <li class="nav-item active">
                    <a href="{{ route('student.pembayaran.index') }}" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Pembayaran</p>
                    </a>
                </li>
                @canApi('siakad.master.refrensi.pengajuan-krs.daftar-matkul')
                <li class="nav-item {{ request()->routeIs('mahasiswa.pengajuan-krs.daftar-matkul') ? 'active' : '' }}">
                    <a href="{{ route('mahasiswa.pengajuan-krs.daftar-matkul') }}">
                        <i class="fas fa-home"></i>
                        <p>KRS</p>
                    </a>
                </li>
                @endcanApi

                @canAnyApi(
                'siakad.master.refrensi.dosen-verifikasi-krs.daftar-verifikasi',
                'siakad.master.refrensi.dosen-matakuliah.get-mahasiswa'
                )
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Dosen</h4>
                </li>
                @canApi('siakad.master.refrensi.dosen-verifikasi-krs.daftar-verifikasi')
                <li
                    class="nav-item {{ request()->routeIs('dosen-verifikasi-krs.daftar-verifikasi') ? 'active' : '' }}">
                    <a href="{{ route('dosen-verifikasi-krs.daftar-verifikasi') }}">
                        <i class="fas fa-home"></i>
                        <p>Verifikasi KRS</p>
                    </a>
                </li>
                @endcanApi
                @canApi('siakad.master.refrensi.dosen-matakuliah.get-mahasiswa')
                <li class="nav-item {{ request()->routeIs('dosenmk.getmahasiswa') ? 'active' : '' }}">
                    <a href="{{ route('dosenmk.getmahasiswa') }}">
                        <i class="fas fa-home"></i>
                        <p>Penilaian Mahasiswa</p>
                    </a>
                </li>
                @endcanApi
                @endcanAnyApi

                @canAnyApi(
                'siakad.master.refrensi.prodi.index',
                'siakad.master.refrensi.tahun-akademik.index',
                'siakad.master.refrensi.kurikulum.index',
                'siakad.master.refrensi.mata-kuliah.index',
                'siakad.master.refrensi.jenis-kelas.index',
                'siakad.master.refrensi.kelas-pararel.index',
                'siakad.master.refrensi.kelas-mk.index',
                'siakad.master.refrensi.jenis-pembayaran.index',
                'siakad.master.refrensi.ruang.index',
                'siakad.master.refrensi.dosen.index',
                'siakad.master.refrensi.mahasiswa.index',
                'siakad.master.refrensi.mahasiswa-baru.index',
                )
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">SIAKAD</h4>
                </li>
                <li
                    class="nav-item {{ request()->routeIs([
                        'prodi.*',
                        'tahun-akademik.*',
                        'kurikulum.*',
                        'mata-kuliah.*',
                        'jenis-kelas.*',
                        'kelas-pararel.*',
                        'kelas-mk.*',
                        'jenis-pembayaran.*',
                        'ruang.*',
                        'dosen.*',
                        'mahasiswa.*',
                        'mahasiswa.baru.*',
                    ])
                        ? 'active'
                        : '' }}">
                    <a data-bs-toggle="collapse" href="#mastersiakad"
                        class="{{ !request()->routeIs('mahasiswa.baru.*') &&
                        request()->routeIs([
                            'prodi.*',
                            'tahun-akademik.*',
                            'kurikulum.*',
                            'mata-kuliah.*',
                            'jenis-kelas.*',
                            'kelas-pararel.*',
                            'kelas-mk.*',
                            'jenis-pembayaran.*',
                            'ruang.*',
                            'dosen.*',
                            'mahasiswa.*',
                        ])
                            ? ''
                            : (request()->routeIs([
                                'prodi.*',
                                'tahun-akademik.*',
                                'kurikulum.*',
                                'mata-kuliah.*',
                                'jenis-kelas.*',
                                'kelas-pararel.*',
                                'kelas-mk.*',
                                'jenis-pembayaran.*',
                                'ruang.*',
                                'dosen.*',
                                'mahasiswa.*',
                                'mahasiswa.baru.*',
                            ])
                                ? ''
                                : 'collapsed') }}"
                        aria-expanded="{{ request()->routeIs([
                            'prodi.*',
                            'tahun-akademik.*',
                            'kurikulum.*',
                            'mata-kuliah.*',
                            'jenis-kelas.*',
                            'kelas-pararel.*',
                            'kelas-mk.*',
                            'jenis-pembayaran.*',
                            'ruang.*',
                            'dosen.*',
                            'mahasiswa.*',
                            'mahasiswa.baru.*',
                        ])
                            ? 'true'
                            : 'false' }}">
                        <i class="fas fa-home"></i>
                        <p>Master Data</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs([
                        'prodi.*',
                        'tahun-akademik.*',
                        'kurikulum.*',
                        'mata-kuliah.*',
                        'jenis-kelas.*',
                        'kelas-pararel.*',
                        'kelas-mk.*',
                        'jenis-pembayaran.*',
                        'ruang.*',
                        'dosen.*',
                        'mahasiswa.*',
                        'mahasiswa.baru.*',
                    ])
                        ? 'show'
                        : '' }}"
                        id="mastersiakad">
                        <ul class="nav nav-collapse">
                            <!-- REFERENSI -->
                            <li
                                class="{{ request()->routeIs([
                                    'prodi.*',
                                    'tahun-akademik.*',
                                    'kurikulum.*',
                                    'mata-kuliah.*',
                                    'jenis-kelas.*',
                                    'kelas-pararel.*',
                                    'kelas-mk.*',
                                    'jenis-pembayaran.*',
                                    'ruang.*',
                                ])
                                    ? 'active'
                                    : '' }}">
                                <a data-bs-toggle="collapse" href="#subnavRef"
                                    class="{{ request()->routeIs([
                                        'prodi.*',
                                        'tahun-akademik.*',
                                        'kurikulum.*',
                                        'mata-kuliah.*',
                                        'jenis-kelas.*',
                                        'kelas-pararel.*',
                                        'kelas-mk.*',
                                        'jenis-pembayaran.*',
                                        'ruang.*',
                                    ])
                                        ? ''
                                        : 'collapsed' }}">
                                    <span class="sub-item">Referensi</span>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse {{ request()->routeIs([
                                    'prodi.*',
                                    'tahun-akademik.*',
                                    'kurikulum.*',
                                    'mata-kuliah.*',
                                    'jenis-kelas.*',
                                    'kelas-pararel.*',
                                    'kelas-mk.*',
                                    'jenis-pembayaran.*',
                                    'ruang.*',
                                ])
                                    ? 'show'
                                    : '' }}"
                                    id="subnavRef">
                                    <ul class="nav nav-collapse subnav">
                                        @canApi('siakad.master.refrensi.prodi.index')
                                        <li class="{{ request()->routeIs('prodi.*') ? 'active' : '' }}">
                                            <a href="{{ route('prodi.index') }}">
                                                <span class="sub-item">Program Studi</span>
                                            </a>
                                        </li>
                                        @endcanApi
                                        @canApi('siakad.master.refrensi.tahun-akademik.index')
                                        <li class="{{ request()->routeIs('tahun-akademik.*') ? 'active' : '' }}">
                                            <a href="{{ route('tahun-akademik.index') }}">
                                                <span class="sub-item">Tahun Akademik</span>
                                            </a>
                                        </li>
                                        @endcanApi
                                        @canApi('siakad.master.refrensi.mata-kuliah.index')
                                        <li class="{{ request()->routeIs('mata-kuliah.*') ? 'active' : '' }}">
                                            <a href="{{ route('mata-kuliah.indexProdi') }}">
                                                <span class="sub-item">Mata Kuliah</span>
                                            </a>
                                        </li>
                                        @endcanApi
                                        @canApi('siakad.master.refrensi.kurikulum.index')
                                        <li class="{{ request()->routeIs('kurikulum.*') ? 'active' : '' }}">
                                            <a href="{{ route('kurikulum.index') }}">
                                                <span class="sub-item">Kurikulum</span>
                                            </a>
                                        </li>
                                        @endcanApi
                                        @canApi('siakad.master.refrensi.jenis-kelas.index')
                                        <li class="{{ request()->routeIs('jenis-kelas.*') ? 'active' : '' }}">
                                            <a href="{{ route('jenis-kelas.index') }}">
                                                <span class="sub-item">Jenis Kelas</span>
                                            </a>
                                        </li>
                                        @endcanApi
                                        @canApi('siakad.master.refrensi.kelas-pararel.index')
                                        <li class="{{ request()->routeIs('kelas-pararel.*') ? 'active' : '' }}">
                                            <a href="{{ route('kelas-pararel.index') }}">
                                                <span class="sub-item">Kelas Pararel</span>
                                            </a>
                                        </li>
                                        @endcanApi
                                        @canApi('siakad.master.refrensi.kelas-mk.index')
                                        <li class="{{ request()->routeIs('kelas-mk.*') ? 'active' : '' }}">
                                            <a href="{{ route('kelas-mk.index') }}">
                                                <span class="sub-item">Kelas Mata Kuliah</span>
                                            </a>
                                        </li>
                                        @endcanApi
                                        @canApi('siakad.master.refrensi.jenis-pembayaran.index')
                                        <li class="{{ request()->routeIs('jenis-pembayaran.*') ? 'active' : '' }}">
                                            <a href="{{ route('jenis-pembayaran.index') }}">
                                                <span class="sub-item">Jenis Pembayaran</span>
                                            </a>
                                        </li>
                                        @endcanApi
                                        @canApi('siakad.master.refrensi.ruang.index')
                                        <li class="{{ request()->routeIs('ruang.*') ? 'active' : '' }}">
                                            <a href="{{ route('ruang.index') }}">
                                                <span class="sub-item">Ruang Kuliah</span>
                                            </a>
                                        </li>
                                        @endcanApi
                                    </ul>
                                </div>
                            </li>
                            @canApi('siakad.master.refrensi.dosen.index')
                            <li class="nav-item {{ request()->routeIs('dosen.*') ? 'active' : '' }}">
                                <a href="{{ route('dosen.index') }}">
                                    <i class="fas fa-home"></i>
                                    <p>Data Dosen</p>
                                </a>
                            </li>
                            @endcanApi

                            @canApi('siakad.master.refrensi.mahasiswa.index')
                            <li
                                class="nav-item {{ request()->routeIs('mahasiswa.*') && !request()->routeIs('mahasiswa.baru.*') ? 'active' : '' }}">
                                <a href="{{ route('mahasiswa.index') }}">
                                    <i class="fas fa-home"></i>
                                    <p>Data Mahasiswa</p>
                                </a>
                            </li>
                            @endcanApi

                            @canApi('siakad.master.refrensi.mahasiswa-baru.index')
                            <li class="nav-item {{ request()->routeIs('mahasiswa.baru.*') ? 'active' : '' }}">
                                <a href="{{ route('mahasiswa.baru.index') }}">
                                    <i class="fas fa-home"></i>
                                    <p>Data Mahasiswa Baru</p>
                                </a>
                            </li>
                            @endcanApi
                        </ul>
                    </div>
                </li>
                @endcanAnyApi

                @canAnyApiPengguna(
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

                <li
                    class="nav-item {{ request()->routeIs(['users.*', 'roles.*', 'permissions.*']) ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#masterpengguna"
                        class="{{ request()->routeIs(['users.*', 'roles.*', 'permissions.*']) ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->routeIs(['users.*', 'roles.*', 'permissions.*']) ? 'true' : 'false' }}">
                        <i class="fas fa-home"></i>
                        <p>Setting</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ request()->routeIs(['users.*', 'roles.*', 'permissions.*']) ? 'show' : '' }}"
                        id="masterpengguna">
                        <ul class="nav nav-collapse">

                            @canApiPengguna('pengguna.setting.users.index')
                            <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <a href="{{ route('users.index') }}">
                                    <span class="sub-item">User</span>
                                </a>
                            </li>
                            @endcanApiPengguna

                            @canApiPengguna('pengguna.setting.roles.index')
                            <li class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <a href="{{ route('roles.index') }}">
                                    <span class="sub-item">Role</span>
                                </a>
                            </li>
                            @endcanApiPengguna

                            @canApiPengguna('pengguna.setting.permissions.index')
                            <li class="{{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                                <a href="{{ route('permissions.index') }}">
                                    <span class="sub-item">Permission</span>
                                </a>
                            </li>
                            @endcanApiPengguna

                        </ul>
                    </div>
                </li>

                @endcanAnyApiPengguna


                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Sign-Out</h4>
                </li>
                <li class="nav-item {{ request()->routeIs('logout') ? 'active' : '' }}">
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
