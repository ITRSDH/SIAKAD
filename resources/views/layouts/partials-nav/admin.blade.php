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
    <h4 class="text-section">CMS Kampus</h4>
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
        'profile-dosen.*',
        'pmb-pendaftaran.*',
        'sertifikat-akreditasi.*',
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
            'profile-dosen.*',
            'pmb-pendaftaran.*',
            'sertifikat-akreditasi.*',
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
            'profile-dosen.*',
            'pmb-pendaftaran.*',
            'sertifikat-akreditasi.*',
        ])
            ? 'true'
            : 'false' }}">
        <i class="fas fa-home"></i>
        <p>Website Kampus</p>
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
        'profile-dosen.*',
        'pmb-pendaftaran.*',
        'sertifikat-akreditasi.*',
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
            @canApi('websitekampus.landing.profile-dosen.index')
            <li class="{{ request()->routeIs('profile-dosen.*') ? 'active' : '' }}">
                <a href="{{ route('profile-dosen.index') }}">
                    <span class="sub-item">Profile dosen</span>
                </a>
            </li>
            @endcanApi
            @canApi('websitekampus.landing.pmb-pendaftaran.index')
            <li class="{{ request()->routeIs('pmb-pendaftaran.*') ? 'active' : '' }}">
                <a href="{{ route('pmb-pendaftaran.index') }}">
                    <span class="sub-item">PMB Pendaftaran</span>
                </a>
            </li>
            @endcanApi
            @canApi('websitekampus.landing.sertifikat-akreditasi.index')
            <li class="{{ request()->routeIs('sertifikat-akreditasi.*') ? 'active' : '' }}">
                <a href="{{ route('sertifikat-akreditasi.index') }}">
                    <span class="sub-item">Sertifikat Akreditasi</span>
                </a>
            </li>
            @endcanApi
        </ul>
    </div>
</li>
@endcanAnyApi

@php
    $masterRoutes = ['prodi.*', 'tahun-akademik.*', 'kurikulum.*', 'mata-kuliah.*'];

    $baakWorkspaceRoutes = ['workspace.baak'];
    $dosenRoutes = ['aktor-akademik.*', 'dosen.*', 'dosen-wali.*', 'prodi.*', 'users.*'];
    $mahasiswaRoutes = ['mahasiswa.*', 'mahasiswa.baru.*', 'akademik.administrasi-studi.*'];
    $transaksiRoutes = ['kelas-kuliah.*'];
    $monitoringAkademikRoutes = [
        'akademik.monitoring',
        'akademik.khs.*',
        'akademik.riwayat-studi.*',
        'akademik.administrasi-studi.*',
        'akademik.kurikulum-mahasiswa.*',
    ];
    $capaianRoutes = ['capaian.*'];
    $akhirStudiRoutes = ['tugas-akhir.*', 'yudisium.*', 'kelulusan.*', 'akhir-studi.monitoring'];
    $administratifRoutes = ['wisuda.*'];

    $isMasterActive = request()->routeIs($masterRoutes);
    $isBaakWorkspaceActive = request()->routeIs($baakWorkspaceRoutes);
    $isDosenActive = request()->routeIs($dosenRoutes);
    $isMahasiswaActive = request()->routeIs($mahasiswaRoutes);
    $isTransaksiActive = request()->routeIs($transaksiRoutes);
    $isMonitoringAkademikActive = request()->routeIs($monitoringAkademikRoutes);
    $isCapaianActive = request()->routeIs($capaianRoutes);
    $isAkhirStudiActive = request()->routeIs($akhirStudiRoutes);
    $isAdministratifActive = request()->routeIs($administratifRoutes);
    $userRoles = session('user.role', []);
    $userPermissions = session('user.permission', []);
    $khsImportPermissions = [
        'akademik.khs.import.index',
        'akademik.khs.import.history',
        'akademik.khs.import.show',
        'akademik.khs.import.preview',
        'akademik.khs.import.process',
        'akademik.khs.import.rollback',
        'akademik.khs.import.export-errors',
        'akademik.khs.import.export-results',
        'akademik.khs.import.template-export',
        'akademik.khs.show',
        'akademik.khs.details.update',
        'akademik.khs.finalize',
    ];
    $historicalPermissions = [
        'akademik.krs-historical.filters',
        'akademik.krs-historical.eligible-mahasiswa',
        'akademik.krs-historical.package-classes',
        'akademik.krs-historical.preview.build',
        'akademik.krs-historical.execute.build',
        'akademik.krs-historical.preview.reopen',
        'akademik.krs-historical.execute.reopen',
        'akademik.krs-historical.preview.refinalize',
        'akademik.krs-historical.execute.refinalize',
        'akademik.krs-historical.preview.reset',
        'akademik.krs-historical.execute.reset',
        'akademik.krs-historical.preview.generate-khs',
        'akademik.krs-historical.execute.generate-khs',
        'akademik.krs-historical.batches',
        'akademik.krs-historical.batches.show',
    ];
    $studentStudyPermissions = [
        'akademik.krs-historical.filters',
        'akademik.krs-historical.batches',
        'akademik.khs.import.index',
        'akademik.khs.import.history',
        'akademik.khs.index',
    ];
    $canAccessKhsImport =
        in_array('admin', $userRoles, true) || count(array_intersect($khsImportPermissions, $userPermissions)) > 0;
    $canAccessHistorical =
        in_array('admin', $userRoles, true) || count(array_intersect($historicalPermissions, $userPermissions)) > 0;
    $canAccessStudentStudy =
        in_array('admin', $userRoles, true) || count(array_intersect($studentStudyPermissions, $userPermissions)) > 0;
@endphp

{{-- ================= MASTER DATA ================= --}}
@canAnyApi(
'siakad.master.refrensi.prodi.index',
'siakad.master.refrensi.tahun-akademik.index',
'siakad.master.refrensi.kurikulum.index',
'siakad.master.refrensi.mata-kuliah.index',
'siakad.master.refrensi.ruang-kuliah.index',
'siakad.master.refrensi.periode-krs.index'
)
<li class="nav-section">
    <span class="sidebar-mini-icon">
        <i class="fa fa-ellipsis-h"></i>
    </span>
    <h4 class="text-section">Akademik</h4>
</li>
<li class="nav-item {{ $isBaakWorkspaceActive ? 'active' : '' }}">
    <a href="{{ route('workspace.baak') }}">
        <i class="fas fa-briefcase"></i>
        <p>Workspace BAAK</p>
    </a>
</li>
<li class="nav-item {{ $isMasterActive ? 'active' : '' }}">
    <a data-bs-toggle="collapse" href="#masterdata" class="{{ $isMasterActive ? '' : 'collapsed' }}">
        <i class="fas fa-database"></i>
        <p>Master Akademik</p>
        <span class="caret"></span>
    </a>

    <div class="collapse {{ $isMasterActive ? 'show' : '' }}" id="masterdata">
        <ul class="nav nav-collapse">

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
                    <span class="sub-item">Struktur Kurikulum</span>
                </a>
            </li>
            <li
                class="{{ request()->routeIs('kurikulum-induk.*') || request()->routeIs('jenis-kurikulum.*') ? 'active' : '' }}">
                <a href="{{ route('kurikulum-induk.index') }}">
                    <span class="sub-item">Tahun Kurikulum</span>
                </a>
            </li>
            @endcanApi

            @canApi('siakad.master.refrensi.ruang-kuliah.index')
            <li class="{{ request()->routeIs('ruang-kuliah.*') ? 'active' : '' }}">
                <a href="{{ route('ruang-kuliah.index') }}">
                    <span class="sub-item">Ruang Kuliah</span>
                </a>
            </li>
            @endcanApi

            @canApi('siakad.master.refrensi.periode-krs.index')
            <li class="{{ request()->routeIs('periode-krs.*') ? 'active' : '' }}">
                <a href="{{ route('periode-krs.index') }}">
                    <span class="sub-item">Periode KRS</span>
                </a>
            </li>
            @endcanApi

        </ul>
    </div>
</li>
@endcanAnyApi

{{-- <li class="nav-item {{ $isCapaianActive ? 'active' : '' }}">
    <a href="{{ route('capaian.indexProdi') }}">
        <i class="fas fa-bullseye"></i>
        <p>Capaian Pembelajaran</p>
    </a>
</li> --}}

<li class="nav-item {{ $isTransaksiActive ? 'active' : '' }}">
    <a href="{{ route('kelas-kuliah.index') }}">
        <i class="fas fa-chalkboard"></i>
        <p>Kelas Kuliah</p>
    </a>
</li>

<li class="nav-item {{ $isMonitoringAkademikActive ? 'active' : '' }}">
    <a data-bs-toggle="collapse" href="#monitoringAkademikMenu"
        class="{{ $isMonitoringAkademikActive ? '' : 'collapsed' }}">
        <i class="fas fa-chart-line"></i>
        <p>Monitoring Akademik</p>
        <span class="caret"></span>
    </a>

    <div class="collapse {{ $isMonitoringAkademikActive ? 'show' : '' }}" id="monitoringAkademikMenu">
        <ul class="nav nav-collapse">
            {{-- <li class="{{ request()->routeIs('akademik.monitoring') ? 'active' : '' }}">
                <a href="{{ route('akademik.monitoring') }}">
                    <span class="sub-item">Dashboard Akademik</span>
                </a>
            </li> --}}
            @if ($canAccessStudentStudy)
                <li class="{{ request()->routeIs('akademik.administrasi-studi.*') ? 'active' : '' }}">
                    <a href="{{ route('akademik.administrasi-studi.index') }}">
                        <span class="sub-item">Administrasi Studi Mahasiswa</span>
                    </a>
                </li>
            @endif
            @canApi('siakad.master.refrensi.mahasiswa.index')
                <li class="{{ request()->routeIs('akademik.kurikulum-mahasiswa.*') ? 'active' : '' }}">
                    <a href="{{ route('akademik.kurikulum-mahasiswa.index') }}">
                        <span class="sub-item">Kurikulum Mahasiswa</span>
                    </a>
                </li>
            @endcanApi
        </ul>
    </div>
</li>

{{-- <li class="nav-item {{ $isAkhirStudiActive ? 'active' : '' }}">
    <a data-bs-toggle="collapse" href="#akhirStudiMenu" class="{{ $isAkhirStudiActive ? '' : 'collapsed' }}">
        <i class="fas fa-graduation-cap"></i>
        <p>Akhir Studi</p>
        <span class="caret"></span>
    </a>

    <div class="collapse {{ $isAkhirStudiActive ? 'show' : '' }}" id="akhirStudiMenu">
        <ul class="nav nav-collapse">
            <li>
                <a href="{{ route('akhir-studi.monitoring') }}">
                    <span class="sub-item">Monitoring</span>
                </a>
            </li>
            <li>
                <a href="{{ route('tugas-akhir.index') }}">
                    <span class="sub-item">Tugas Akhir</span>
                </a>
            </li>
            <li>
                <a href="{{ route('yudisium.index') }}">
                    <span class="sub-item">Yudisium</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kelulusan.index') }}">
                    <span class="sub-item">Kelulusan</span>
                </a>
            </li>
        </ul>
    </div>
</li> --}}

{{-- <li class="nav-item {{ $isAdministratifActive ? 'active' : '' }}">
    <a data-bs-toggle="collapse" href="#administratifMenu" class="{{ $isAdministratifActive ? '' : 'collapsed' }}">
        <i class="fas fa-clipboard-check"></i>
        <p>Administratif</p>
        <span class="caret"></span>
    </a>

    <div class="collapse {{ $isAdministratifActive ? 'show' : '' }}" id="administratifMenu">
        <ul class="nav nav-collapse">
            <li>
                <a href="{{ route('wisuda.periode.index') }}">
                    <span class="sub-item">Wisuda</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" class="text-muted">
                    <span class="sub-item">Dokumen & Layanan</span>
                    <span class="badge badge-secondary ms-2">Backlog</span>
                </a>
            </li>
            <li>
                <a href="{{ route('akademik.monitoring') }}">
                    <span class="sub-item">Monitoring Akademik</span>
                </a>
            </li>
        </ul>
    </div>
</li> --}}


{{-- ================= DOSEN ================= --}}
@canAnyApi(
'siakad.master.refrensi.dosen.index',
'siakad.master.refrensi.prodi.index'
)
<li class="nav-item {{ $isDosenActive ? 'active' : '' }}">
    <a data-bs-toggle="collapse" href="#dosenMenu" class="{{ $isDosenActive ? '' : 'collapsed' }}">
        <i class="fas fa-user-tie"></i>
        <p>Aktor Akademik</p>
        <span class="caret"></span>
    </a>

    <div class="collapse {{ $isDosenActive ? 'show' : '' }}" id="dosenMenu">
        <ul class="nav nav-collapse">
            <li class="{{ request()->routeIs('aktor-akademik.index') ? 'active' : '' }}">
                <a href="{{ route('aktor-akademik.index') }}">
                    <span class="sub-item">Ringkasan Aktor</span>
                </a>
            </li>

            @canApi('siakad.master.refrensi.dosen.index')
            <li class="{{ request()->routeIs('dosen.*') ? 'active' : '' }}">
                <a href="{{ route('dosen.index') }}">
                    <span class="sub-item">Dosen</span>
                </a>
            </li>
            @endcanApi

            @canApi('siakad.master.refrensi.prodi.index')
            <li
                class="{{ request()->routeIs('aktor-akademik.kaprodi') || request()->routeIs('prodi.*') ? 'active' : '' }}">
                <a href="{{ route('aktor-akademik.kaprodi') }}">
                    <span class="sub-item">Ketua Program Studi</span>
                </a>
            </li>
            @endcanApi

            @canApi('siakad.master.refrensi.dosen.index')
            <li
                class="{{ request()->routeIs('aktor-akademik.pembimbing-akademik') || request()->routeIs('dosen-wali.*') ? 'active' : '' }}">
                <a href="{{ route('aktor-akademik.pembimbing-akademik') }}">
                    <span class="sub-item">Pembimbing Akademik</span>
                </a>
            </li>
            @endcanApi

            @canApiPengguna('pengguna.setting.users.index')
            <li class="{{ request()->routeIs('workspace.baak') ? 'active' : '' }}">
                <a href="{{ route('workspace.baak') }}">
                    <span class="sub-item">BAAK</span>
                </a>
            </li>
            @endcanApiPengguna
        </ul>
    </div>
</li>
@endcanAnyApi


{{-- ================= MAHASISWA ================= --}}
@canAnyApi(
'siakad.master.refrensi.mahasiswa.index',
'siakad.master.refrensi.mahasiswa-baru.index'
)
<li class="nav-item {{ $isMahasiswaActive ? 'active' : '' }}">
    <a data-bs-toggle="collapse" href="#mahasiswaMenu" class="{{ $isMahasiswaActive ? '' : 'collapsed' }}">
        <i class="fas fa-users"></i>
        <p>Mahasiswa</p>
        <span class="caret"></span>
    </a>

    <div class="collapse {{ $isMahasiswaActive ? 'show' : '' }}" id="mahasiswaMenu">
        <ul class="nav nav-collapse">

            @canApi('siakad.master.refrensi.mahasiswa.index')
            <li
                class="{{ request()->routeIs('mahasiswa.*') && !request()->routeIs('mahasiswa.baru.*') ? 'active' : '' }}">
                <a href="{{ route('mahasiswa.index') }}">
                    <span class="sub-item">Mahasiswa</span>
                </a>
            </li>
            @endcanApi

            @canApi('siakad.master.refrensi.mahasiswa-baru.index')
            <li class="{{ request()->routeIs('mahasiswa.baru.*') ? 'active' : '' }}">
                <a href="{{ route('mahasiswa.baru.index') }}">
                    <span class="sub-item">Mahasiswa Baru</span>
                </a>
            </li>
            @endcanApi

            @if ($canAccessStudentStudy)
            <li class="{{ request()->routeIs('akademik.administrasi-studi.*') ? 'active' : '' }}">
                <a href="{{ route('akademik.administrasi-studi.index', ['tab' => 'historical']) }}">
                    <span class="sub-item">Migrasi Riwayat Studi</span>
                </a>
            </li>
            @endif

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
    <h4 class="text-section">Pengaturan</h4>
</li>

<li class="nav-item {{ request()->routeIs(['users.*', 'roles.*', 'permissions.*']) ? 'active' : '' }}">
    <a data-bs-toggle="collapse" href="#masterpengguna"
        class="{{ request()->routeIs(['users.*', 'roles.*', 'permissions.*']) ? '' : 'collapsed' }}"
        aria-expanded="{{ request()->routeIs(['users.*', 'roles.*', 'permissions.*']) ? 'true' : 'false' }}">
        <i class="fas fa-home"></i>
        <p>Manajemen Pengguna</p>
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
