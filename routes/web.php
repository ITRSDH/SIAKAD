<?php

use App\Http\Controllers\Mahasiswa\PembayaranController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MasterData\ProdiController;
use App\Http\Controllers\MasterData\RuangController;
use App\Http\Controllers\MasterData\SemesterController;
use App\Http\Controllers\MasterData\KurikulumController;
use App\Http\Controllers\MasterData\JenisKelasController;
use App\Http\Controllers\MasterData\MataKuliahController;
use App\Http\Controllers\ManagementPengguna\RoleController;
use App\Http\Controllers\ManagementPengguna\UserController;
use App\Http\Controllers\MasterData\KelasPararelController;
use App\Http\Controllers\MasterData\TahunAkademikController;
use App\Http\Controllers\MasterData\JenisPembayaranController;
use App\Http\Controllers\MasterData\JenjangPendidikanController;
use App\Http\Controllers\ManagementPengguna\PermissionController;

// Route Website
use App\Http\Controllers\Website\PengumumanController;
use App\Http\Controllers\Website\PrestasiController;
use App\Http\Controllers\Website\BeasiswaController;
use App\Http\Controllers\Website\BeritaController;
use App\Http\Controllers\Website\GaleriController;
use App\Http\Controllers\Website\FaqController;
use App\Http\Controllers\Website\LandingContentController;
use App\Http\Controllers\Website\OrmawaController;
use App\Http\Controllers\Website\ProfileKampusController;

Route::middleware(['guest.token'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('refresh.token');
});

Route::middleware(['require.token', 'refresh.token'])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard.index');
    // });

    Route::get('/', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/permissions/sync', [PermissionController::class, 'sync'])->name('permissions.sync');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    Route::get('/jenjang-pendidikan', [JenjangPendidikanController::class, 'index'])->name('jenjang-pendidikan.index');
    Route::post('/jenjang-pendidikan', [JenjangPendidikanController::class, 'store'])->name('jenjang-pendidikan.store');
    Route::get('/jenjang-pendidikan/{id}', [JenjangPendidikanController::class, 'show'])->name('jenjang-pendidikan.show');
    Route::put('/jenjang-pendidikan/{id}', [JenjangPendidikanController::class, 'update'])->name('jenjang-pendidikan.update');
    Route::delete('/jenjang-pendidikan/{id}', [JenjangPendidikanController::class, 'destroy'])->name('jenjang-pendidikan.destroy');

    Route::get('/prodi', [ProdiController::class, 'index'])->name('prodi.index');
    Route::post('/prodi', [ProdiController::class, 'store'])->name('prodi.store');
    Route::get('/prodi/{id}', [ProdiController::class, 'show'])->name('prodi.show');
    Route::put('/prodi/{id}', [ProdiController::class, 'update'])->name('prodi.update');
    Route::delete('/prodi/{id}', [ProdiController::class, 'destroy'])->name('prodi.destroy');

    Route::get('/tahun-akademik', [TahunAkademikController::class, 'index'])->name('tahun-akademik.index');
    Route::post('/tahun-akademik', [TahunAkademikController::class, 'store'])->name('tahun-akademik.store');
    Route::get('/tahun-akademik/{id}', [TahunAkademikController::class, 'show'])->name('tahun-akademik.show');
    Route::put('/tahun-akademik/{id}', [TahunAkademikController::class, 'update'])->name('tahun-akademik.update');
    Route::delete('/tahun-akademik/{id}', [TahunAkademikController::class, 'destroy'])->name('tahun-akademik.destroy');
    Route::post('/tahun-akademik/{id}/aktif', [TahunAkademikController::class, 'setAktif'])->name('tahun-akademik.setAktif');

    Route::get('/semester', [SemesterController::class, 'index'])->name('semester.index');
    Route::post('/semester', [SemesterController::class, 'store'])->name('semester.store');
    Route::get('/semester/{id}', [SemesterController::class, 'show'])->name('semester.show');
    Route::put('/semester/{id}', [SemesterController::class, 'update'])->name('semester.update');
    Route::delete('/semester/{id}', [SemesterController::class, 'destroy'])->name('semester.destroy');
    Route::post('/semester/{id}/aktif', [SemesterController::class, 'setAktif'])->name('semester.setAktif');

    Route::get('/kurikulum', [KurikulumController::class, 'index'])->name('kurikulum.index');
    Route::post('/kurikulum', [KurikulumController::class, 'store'])->name('kurikulum.store');
    Route::get('/kurikulum/{id}', [KurikulumController::class, 'show'])->name('kurikulum.show');
    Route::put('/kurikulum/{id}', [KurikulumController::class, 'update'])->name('kurikulum.update');
    Route::delete('/kurikulum/{id}', [KurikulumController::class, 'destroy'])->name('kurikulum.destroy');

    Route::get('/matakuliah', [MataKuliahController::class, 'index'])->name('matakuliah.index');
    Route::get('/matakuliah/create', [MataKuliahController::class, 'create'])->name('matakuliah.create');
    Route::post('/matakuliah', [MataKuliahController::class, 'store'])->name('matakuliah.store');
    Route::get('/matakuliah/{id}/edit', [MataKuliahController::class, 'edit'])->name('matakuliah.edit');
    Route::get('/matakuliah/{id}', [MataKuliahController::class, 'show'])->name('matakuliah.show');
    Route::put('/matakuliah/{id}', [MataKuliahController::class, 'update'])->name('matakuliah.update');
    Route::delete('/matakuliah/{id}', [MataKuliahController::class, 'destroy'])->name('matakuliah.destroy');

    Route::get('/jenis-kelas', [JenisKelasController::class, 'index'])->name('jenis-kelas.index');
    Route::post('/jenis-kelas', [JenisKelasController::class, 'store'])->name('jenis-kelas.store');
    Route::get('/jenis-kelas/{id}', [JenisKelasController::class, 'show'])->name('jenis-kelas.show');
    Route::put('/jenis-kelas/{id}', [JenisKelasController::class, 'update'])->name('jenis-kelas.update');
    Route::delete('/jenis-kelas/{id}', [JenisKelasController::class, 'destroy'])->name('jenis-kelas.destroy');

    Route::get('/kelas-pararel', [KelasPararelController::class, 'index'])->name('kelas-pararel.index');
    Route::post('/kelas-pararel', [KelasPararelController::class, 'store'])->name('kelas-pararel.store');
    Route::get('/kelas-pararel/{id}', [KelasPararelController::class, 'show'])->name('kelas-pararel.show');
    Route::put('/kelas-pararel/{id}', [KelasPararelController::class, 'update'])->name('kelas-pararel.update');
    Route::delete('/kelas-pararel/{id}', [KelasPararelController::class, 'destroy'])->name('kelas-pararel.destroy');

    Route::get('/ruang', [RuangController::class, 'index'])->name('ruang.index');
    Route::post('/ruang', [RuangController::class, 'store'])->name('ruang.store');
    Route::get('/ruang/{id}', [RuangController::class, 'show'])->name('ruang.show');
    Route::put('/ruang/{id}', [RuangController::class, 'update'])->name('ruang.update');
    Route::delete('/ruang/{id}', [RuangController::class, 'destroy'])->name('ruang.destroy');

    Route::get('/jenis-pembayaran', [JenisPembayaranController::class, 'index'])->name('jenis-pembayaran.index');
    Route::post('/jenis-pembayaran', [JenisPembayaranController::class, 'store'])->name('jenis-pembayaran.store');
    Route::get('/jenis-pembayaran/{id}', [JenisPembayaranController::class, 'show'])->name('jenis-pembayaran.show');
    Route::put('/jenis-pembayaran/{id}', [JenisPembayaranController::class, 'update'])->name('jenis-pembayaran.update');
    Route::delete('/jenis-pembayaran/{id}', [JenisPembayaranController::class, 'destroy'])->name('jenis-pembayaran.destroy');

    // Route Pengumuman
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/datatable', [PengumumanController::class, 'datatable'])->name('pengumuman.datatable');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}', [PengumumanController::class, 'show'])->name('pengumuman.show');
    Route::put('/pengumuman/{id}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    // Route Prestasi
    Route::get('/prestasi', [PrestasiController::class, 'index'])->name('prestasi.index');
    Route::get('/prestasi/datatable', [PrestasiController::class, 'datatable'])->name('prestasi.datatable');
    Route::post('/prestasi', [PrestasiController::class, 'store'])->name('prestasi.store');
    Route::get('/prestasi/{id}', [PrestasiController::class, 'show'])->name('prestasi.show');
    Route::put('/prestasi/{id}', [PrestasiController::class, 'update'])->name('prestasi.update');
    Route::delete('/prestasi/{id}', [PrestasiController::class, 'destroy'])->name('prestasi.destroy');

    // Route Beasiswa
    Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa.index');
    Route::get('/beasiswa/datatable', [BeasiswaController::class, 'datatable'])->name('beasiswa.datatable');
    Route::post('/beasiswa', [BeasiswaController::class, 'store'])->name('beasiswa.store');
    Route::get('/beasiswa/{id}', [BeasiswaController::class, 'show'])->name('beasiswa.show');
    Route::put('/beasiswa/{id}', [BeasiswaController::class, 'update'])->name('beasiswa.update');
    Route::delete('/beasiswa/{id}', [BeasiswaController::class, 'destroy'])->name('beasiswa.destroy');

    // Route Berita
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/datatable', [BeritaController::class, 'datatable'])->name('berita.datatable');
    Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}', [BeritaController::class, 'show'])->name('berita.show');
    Route::put('/berita/{id}', [BeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');

    // Route Galeri
    Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');
    Route::get('/galeri/datatable', [GaleriController::class, 'datatable'])->name('galeri.datatable');
    Route::post('/galeri', [GaleriController::class, 'store'])->name('galeri.store');
    Route::get('/galeri/{id}', [GaleriController::class, 'show'])->name('galeri.show');
    Route::put('/galeri/{id}', [GaleriController::class, 'update'])->name('galeri.update');
    Route::delete('/galeri/{id}', [GaleriController::class, 'destroy'])->name('galeri.destroy');

    // Route FAQ
    Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
    Route::post('/faq', [FaqController::class, 'store'])->name('faq.store');
    Route::get('/faq/{id}', [FaqController::class, 'show'])->name('faq.show');
    Route::put('/faq/{id}', [FaqController::class, 'update'])->name('faq.update');
    Route::delete('/faq/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');

    // Route Landing Content
    Route::get('/landing', [LandingContentController::class, 'index'])->name('landing-content.index');
    Route::post('/landing', [LandingContentController::class, 'store'])->name('landing-content.store');
    Route::get('/landing/{id?}', [LandingContentController::class, 'show'])->name('landing-content.show');
    Route::put('/landing/{id?}', [LandingContentController::class, 'update'])->name('landing-content.update');
    Route::delete('/landing/{id?}', [LandingContentController::class, 'destroy'])->name('landing-content.destroy');

    // Route Ormawa
    Route::get('/ormawa', [OrmawaController::class, 'index'])->name('ormawa.index');
    Route::post('/ormawa', [OrmawaController::class, 'store'])->name('ormawa.store');
    Route::get('/ormawa/{id}', [OrmawaController::class, 'show'])->name('ormawa.show');
    Route::put('/ormawa/{id}', [OrmawaController::class, 'update'])->name('ormawa.update');
    Route::delete('/ormawa/{id}', [OrmawaController::class, 'destroy'])->name('ormawa.destroy');

    // Route Profile Kampus
    Route::get('/profile-kampus', [ProfileKampusController::class, 'index'])->name('profile-kampus.index');
    Route::post('/profile-kampus', [ProfileKampusController::class, 'store'])->name('profile-kampus.store');
    Route::get('/profile-kampus/{id?}', [ProfileKampusController::class, 'show'])->name('profile-kampus.show');
    Route::put('/profile-kampus/{id?}', [ProfileKampusController::class, 'update'])->name('profile-kampus.update');
    Route::delete('/profile-kampus/{id?}', [ProfileKampusController::class, 'destroy'])->name('profile-kampus.destroy');

    // Route Pembayaran Mahasiswa
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('student.pembayaran.index');
    Route::get('/pembayaran/{tagihanId}/create', [PembayaranController::class, 'create'])->name('student.pembayaran.create');
    Route::post('/pembayaran/{tagihanId}/pay', [PembayaranController::class, 'store'])->name('student.pembayaran.store');
});
