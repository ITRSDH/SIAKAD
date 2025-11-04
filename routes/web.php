<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MasterData\ProdiController;
use App\Http\Controllers\MasterData\RuangController;
use App\Http\Controllers\MasterData\SemesterController;
use App\Http\Controllers\MasterData\KurikulumController;
use App\Http\Controllers\MasterData\JenisKelasController;
use App\Http\Controllers\MasterData\MataKuliahController;
use App\Http\Controllers\MasterData\KelasPararelController;
use App\Http\Controllers\MasterData\TahunAkademikController;
use App\Http\Controllers\MasterData\JenisPembayaranController;
use App\Http\Controllers\MasterData\JenjangPendidikanController;

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

    // Route::resource('jenjang-pendidikan', JenjangPendidikanController::class);
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
});
