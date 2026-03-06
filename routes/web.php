<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Website\FaqController;
// Route Website
use App\Http\Controllers\Website\BeritaController;
use App\Http\Controllers\Website\GaleriController;
use App\Http\Controllers\Website\OrmawaController;
use App\Http\Controllers\Website\BeasiswaController;
use App\Http\Controllers\Website\PrestasiController;
use App\Http\Controllers\Website\PengumumanController;
use App\Http\Controllers\Mahasiswa\PembayaranController;
use App\Http\Controllers\Website\ProfileKampusController;
use App\Http\Controllers\Website\LandingContentController;

// Route Siakad
use App\Http\Controllers\ManagementPengguna\RoleController;
use App\Http\Controllers\ManagementPengguna\UserController;
use App\Http\Controllers\Siakad\MasterData\DosenController;
use App\Http\Controllers\Siakad\MasterData\ProdiController;
use App\Http\Controllers\Siakad\MasterData\KurikulumController;
use App\Http\Controllers\Siakad\MasterData\MahasiswaController;
use App\Http\Controllers\Siakad\MasterData\MataKuliahController;
use App\Http\Controllers\ManagementPengguna\PermissionController;
use App\Http\Controllers\Siakad\MasterData\MahasiswaBaruController;
use App\Http\Controllers\Siakad\MasterData\TahunAkademikController;

Route::middleware(['guest.token'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('refresh.token');
});

Route::middleware(['require.token', 'refresh.token'])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard.index');
    // });

    Route::get('/', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('profile.change-password');

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

    Route::prefix('prodi')->name('prodi.')->group(function () {
        Route::get('/', [ProdiController::class, 'index'])->name('index');
        Route::post('/', [ProdiController::class, 'store'])->name('store');
        Route::get('/{id}', [ProdiController::class, 'show'])->name('show');
        Route::put('/{id}', [ProdiController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProdiController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/kaprodi', [ProdiController::class, 'updateKaprodi'])->name('updateKaprodi');
    });

    Route::prefix('tahun-akademik')->name('tahun-akademik.')->group(function () {
        Route::get('/', [TahunAkademikController::class, 'index'])->name('index');
        Route::get('/create', [TahunAkademikController::class, 'create'])->name('create');
        Route::post('/', [TahunAkademikController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [TahunAkademikController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TahunAkademikController::class, 'update'])->name('update');
        Route::delete('/{id}', [TahunAkademikController::class, 'destroy'])->name('destroy');

        Route::put('/tahun-aktif/{id}', [TahunAkademikController::class, 'setTahunAktif'])->name('tahun-aktif');
        Route::put('/semester-aktif/{id}', [TahunAkademikController::class, 'setSemesterAktif'])->name('semester-aktif');
    });

    Route::prefix('mata-kuliah')->name('mata-kuliah.')->group(function () {
        Route::get('/prodi', [MataKuliahController::class, 'indexProdi'])->name('indexProdi');
        Route::get('/data', [MataKuliahController::class, 'getDataProdi'])->name('dataProdi');
        Route::get('/prodi/{id_prodi}', [MataKuliahController::class, 'index'])->name('index');
        Route::get('/data/{id_prodi}', [MataKuliahController::class, 'getData'])->name('data');
        Route::get('/prodi/{id_prodi}/tambah', [MataKuliahController::class, 'create'])->name('create');
        Route::post('/prodi/{id_prodi}', [MataKuliahController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [MataKuliahController::class, 'detail'])->name('detail');
        Route::put('/{id}/prodi/{id_prodi}', [MataKuliahController::class, 'update'])->name('update');
        Route::delete('/{id}', [MataKuliahController::class, 'destroy'])->name('destroy');

        // Import/Export Routes
        Route::post('/import/{id_prodi}', [MataKuliahController::class, 'importExcel'])->name('import');
        Route::get('/export/template/{id_prodi}', [MataKuliahController::class, 'downloadTemplate'])->name('export.template');
        Route::get('/export/data', [MataKuliahController::class, 'exportData'])->name('export.data');
    });

    Route::prefix('kurikulum')->name('kurikulum.')->group(function () {
        Route::get('/data', [KurikulumController::class, 'getDatakurikulum'])->name('dataKurikulum');
        Route::get('/', [KurikulumController::class, 'index'])->name('index');
        Route::get('/add', [KurikulumController::class, 'create'])->name('create');
        Route::post('/', [KurikulumController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [KurikulumController::class, 'detail'])->name('detail');
        Route::get('/edit-kolektif/{id}', [KurikulumController::class, 'editkolektif'])->name('edit-kolektif');
        Route::put('/{id}', [KurikulumController::class, 'update'])->name('update');
        Route::delete('/{id}', [KurikulumController::class, 'destroy'])->name('destroy');

        // Tambahkan route tambahan untuk manajemen mata kuliah
        Route::post('/{id}/tambah-mata-kuliah', [KurikulumController::class, 'tambahMataKuliahManual'])->name('tambah-mata-kuliah');
        Route::post('/{id_tujuan}/clone-mata-kuliah', [KurikulumController::class, 'cloneMataKuliah'])->name('clone-mata-kuliah');
        Route::put('/{id}/mata-kuliah/{id_mk}', [KurikulumController::class, 'updateMataKuliah'])->name('update-mata-kuliah');
        Route::delete('/{id}/mata-kuliah/{id_mk}', [KurikulumController::class, 'hapusMataKuliah'])->name('hapus-mata-kuliah');
    });

    // Route Dosen
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/', [DosenController::class, 'index'])->name('index');
        Route::post('/', [DosenController::class, 'store'])->name('store');
        Route::get('/{id}', [DosenController::class, 'show'])->name('show');
        Route::put('/{id}', [DosenController::class, 'update'])->name('update');
        Route::delete('/{id}', [DosenController::class, 'destroy'])->name('destroy');
    });

    // Route Mahasiswa
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/', [MahasiswaController::class, 'index'])->name('index');
        Route::post('/', [MahasiswaController::class, 'store'])->name('store');
        Route::get('/{id}', [MahasiswaController::class, 'show'])->name('show');
        Route::put('/{id}', [MahasiswaController::class, 'update'])->name('update');
        Route::delete('/{id}', [MahasiswaController::class, 'destroy'])->name('destroy');

        // Import/Export Routes
        Route::post('/import/{id_prodi}', [MahasiswaController::class, 'import'])->name('import');
        Route::get('/export/template/{id_prodi}', [MahasiswaController::class, 'exportTemplate'])->name('export.template');
    });

    // Route Mahasiswa Baru
    Route::prefix('mahasiswa-baru')->name('mahasiswa.baru.')->group(function () {
        Route::get('/', [MahasiswaBaruController::class, 'index'])->name('index');
        Route::post('/', [MahasiswaBaruController::class, 'store'])->name('store');
        Route::post('/sync', [MahasiswaBaruController::class, 'sync'])->name('sync');
        Route::get('/{id}', [MahasiswaBaruController::class, 'show'])->name('show');
        Route::put('/{id}', [MahasiswaBaruController::class, 'update'])->name('update');
        Route::delete('/{id}', [MahasiswaBaruController::class, 'destroy'])->name('destroy');
    });

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
