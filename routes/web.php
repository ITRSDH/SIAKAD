<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Siakad\Akademik\MonitoringAkademikController;
use App\Http\Controllers\Siakad\Akademik\KhsController as AkademikKhsController;
use App\Http\Controllers\Siakad\Akademik\KhsImportController as AkademikKhsImportController;
use App\Http\Controllers\Siakad\Akademik\StudentStudyAdministrationController;
use App\Http\Controllers\Siakad\Administratif\BaakWorkspaceController;
use App\Http\Controllers\Siakad\Administratif\DosenPengajarWorkspaceController;
use App\Http\Controllers\Siakad\Administratif\KaprodiWorkspaceController;
use App\Http\Controllers\Siakad\Administratif\PembimbingAkademikWorkspaceController;
use App\Http\Controllers\Mahasiswa\KHSController;
use App\Http\Controllers\Mahasiswa\PembayaranController;
// use App\Http\Controllers\Website\ProfileKampusController;
use App\Http\Controllers\Website\ProfileDosenController;
// use App\Http\Controllers\Website\LandingContentController;
use App\Http\Controllers\Website\PmbPendaftaranController;
use App\Http\Controllers\Website\SertifikatAkreditasi;

// Route Siakad
use App\Http\Controllers\Mahasiswa\TranskripController;
use App\Http\Controllers\ManagementPengguna\PermissionController;
use App\Http\Controllers\ManagementPengguna\RoleController;
use App\Http\Controllers\ManagementPengguna\UserController;
use App\Http\Controllers\Siakad\Krs\KRSDosenWaliController;
use App\Http\Controllers\Siakad\Krs\KRSHistoricalController;
use App\Http\Controllers\Siakad\Krs\KRSMahasiswaController;
use App\Http\Controllers\Siakad\Administratif\WisudaController;
use App\Http\Controllers\Siakad\AkhirStudi\YudisiumController;
use App\Http\Controllers\Siakad\AkhirStudi\KelulusanController;
use App\Http\Controllers\Siakad\AkhirStudi\MonitoringAkhirStudiController;
use App\Http\Controllers\Siakad\AkhirStudi\TugasAkhirController;
use App\Http\Controllers\Siakad\MasterData\AktorAkademikController;
use App\Http\Controllers\Siakad\MasterData\Capaian\CapaianController;
use App\Http\Controllers\Siakad\MasterData\Capaian\CPLController;
use App\Http\Controllers\Siakad\MasterData\Capaian\MappingCPLMKController;
use App\Http\Controllers\Siakad\MasterData\Capaian\MappingPLCPLController;
use App\Http\Controllers\Siakad\MasterData\Capaian\ProfileLulusanController;
use App\Http\Controllers\Siakad\MasterData\DosenController;
use App\Http\Controllers\Siakad\MasterData\DosenPengajarKelasController;
use App\Http\Controllers\Siakad\MasterData\DosenWaliController;
use App\Http\Controllers\Siakad\MasterData\JadwalController as MasterDataJadwalController;
use App\Http\Controllers\Siakad\MasterData\KelaskuliahController;
use App\Http\Controllers\Siakad\MasterData\KurikulumController;
use App\Http\Controllers\Siakad\MasterData\MahasiswaBaruController;
use App\Http\Controllers\Siakad\MasterData\MahasiswaController;
use App\Http\Controllers\Siakad\MasterData\MataKuliahController;
use App\Http\Controllers\Siakad\MasterData\PeriodeKRSController;
use App\Http\Controllers\Siakad\MasterData\ProdiController;
use App\Http\Controllers\Siakad\MasterData\RuangKuliahController;
use App\Http\Controllers\Siakad\MasterData\TahunAkademikController;
use App\Http\Controllers\Siakad\Penilaian\PenilaianController;
use App\Http\Controllers\Siakad\Transaksi\PertemuanPresensiController;
use App\Http\Controllers\Website\BeasiswaController;
use App\Http\Controllers\Website\BeritaController;
use App\Http\Controllers\Website\FaqController;
use App\Http\Controllers\Website\GaleriController;
use App\Http\Controllers\Website\LandingContentController;
use App\Http\Controllers\Website\OrmawaController;
use App\Http\Controllers\Website\PengumumanController;
use App\Http\Controllers\Website\PrestasiController;
use App\Http\Controllers\Website\ProfileKampusController;
use Illuminate\Support\Facades\Route;

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

    Route::get('/workspace/baak', [BaakWorkspaceController::class, 'index'])->name('workspace.baak');
    Route::get('/workspace/dosen-pengajar', [DosenPengajarWorkspaceController::class, 'index'])->name('workspace.dosen-pengajar');
    Route::get('/workspace/kaprodi', [KaprodiWorkspaceController::class, 'index'])->name('workspace.kaprodi');
    Route::get('/workspace/pembimbing-akademik', [PembimbingAkademikWorkspaceController::class, 'index'])->name('workspace.pembimbing-akademik');

    Route::prefix('akademik/khs')->name('akademik.khs.')->group(function () {
        Route::get('/import', [AkademikKhsImportController::class, 'index'])->name('import.index');
        Route::get('/import/history', [AkademikKhsImportController::class, 'history'])->name('import.history');
        Route::get('/import/template/export', [AkademikKhsImportController::class, 'exportTemplate'])->name('import.template-export');
        Route::post('/import/upload', [AkademikKhsImportController::class, 'upload'])->name('import.upload');
        Route::get('/import/{batch}', [AkademikKhsImportController::class, 'show'])->name('import.show');
        Route::get('/import/{batch}/preview', [AkademikKhsImportController::class, 'preview'])->name('import.preview');
        Route::post('/import/{batch}/process', [AkademikKhsImportController::class, 'process'])->name('import.process');
        Route::post('/import/{batch}/finalize', [AkademikKhsImportController::class, 'finalizeBatch'])->name('import.finalize');
        Route::post('/import/{batch}/rollback', [AkademikKhsImportController::class, 'rollback'])->name('import.rollback');
        Route::get('/import/{batch}/export-errors', [AkademikKhsImportController::class, 'exportErrors'])->name('import.export-errors');
        Route::get('/import/{batch}/export-results', [AkademikKhsImportController::class, 'exportResults'])->name('import.export-results');

        Route::get('/{khsId}', [AkademikKhsController::class, 'show'])->name('show');
        Route::put('/{khsId}/details/{detailId}', [AkademikKhsController::class, 'updateDetail'])->name('details.update');
        Route::put('/{khsId}/summary', [AkademikKhsController::class, 'updateSummary'])->name('summary.update');
        Route::post('/{khsId}/finalize', [AkademikKhsController::class, 'finalize'])->name('finalize');
    });

    Route::prefix('akademik/riwayat-studi')->name('akademik.riwayat-studi.')->group(function () {
        Route::get('/', [KRSHistoricalController::class, 'index'])->name('index');
        Route::get('/eligible-mahasiswa', [KRSHistoricalController::class, 'eligibleMahasiswa'])->name('eligible');
        Route::get('/historical-classes', [KRSHistoricalController::class, 'historicalClasses'])->name('classes');
        Route::get('/package-classes', [KRSHistoricalController::class, 'packageClasses'])->name('package-classes');
        Route::post('/preview', [KRSHistoricalController::class, 'preview'])->name('preview');
        Route::post('/execute', [KRSHistoricalController::class, 'execute'])->name('execute');
        Route::get('/batches', [KRSHistoricalController::class, 'batchHistory'])->name('batches');
        Route::get('/batches/{id}', [KRSHistoricalController::class, 'showBatch'])->name('batches.show');
    });

    Route::prefix('akademik/administrasi-studi')->name('akademik.administrasi-studi.')->group(function () {
        Route::get('/', [StudentStudyAdministrationController::class, 'index'])->name('index');
        Route::get('/krs', [StudentStudyAdministrationController::class, 'krsPage'])->name('krs');
        Route::get('/nilai', [StudentStudyAdministrationController::class, 'nilaiPage'])->name('nilai');
        Route::get('/khs', [StudentStudyAdministrationController::class, 'khsPage'])->name('khs');
        Route::get('/riwayat', [StudentStudyAdministrationController::class, 'riwayatPage'])->name('riwayat');
        Route::get('/summary', [StudentStudyAdministrationController::class, 'summary'])->name('summary');
        Route::get('/historical/eligible-mahasiswa', [StudentStudyAdministrationController::class, 'eligibleHistoricalStudents'])->name('historical.eligible');
        Route::get('/historical/package-classes', [StudentStudyAdministrationController::class, 'historicalPackageClasses'])->name('historical.package-classes');
        Route::get('/historical/repeat-candidates', [StudentStudyAdministrationController::class, 'historicalRepeatCandidates'])->name('historical.repeat-candidates');
        Route::post('/historical/preview', [StudentStudyAdministrationController::class, 'previewHistorical'])->name('historical.preview');
        Route::post('/historical/execute', [StudentStudyAdministrationController::class, 'executeHistorical'])->name('historical.execute');
        Route::get('/ready-khs', [StudentStudyAdministrationController::class, 'readyForKhs'])->name('ready-khs');
        Route::post('/nilai-manual/save', [StudentStudyAdministrationController::class, 'saveManualNilai'])->name('nilai-manual.save');
        Route::get('/nilai-manual/context', [StudentStudyAdministrationController::class, 'manualNilaiContext'])->name('nilai-manual.context');
        Route::get('/generate-khs/preview', [StudentStudyAdministrationController::class, 'previewGenerateKhs'])->name('generate-khs.preview');
        Route::post('/generate-khs/execute', [StudentStudyAdministrationController::class, 'executeGenerateKhs'])->name('generate-khs.execute');
        Route::get('/import/template/export', [StudentStudyAdministrationController::class, 'exportImportTemplate'])->name('import.template-export');
        Route::post('/import/upload', [StudentStudyAdministrationController::class, 'uploadImport'])->name('import.upload');
        Route::get('/import/{batch}/preview', [StudentStudyAdministrationController::class, 'previewImportBatch'])->name('import.preview');
        Route::post('/import/{batch}/process', [StudentStudyAdministrationController::class, 'processImportBatch'])->name('import.process');
        Route::post('/import/{batch}/finalize', [StudentStudyAdministrationController::class, 'finalizeImportBatch'])->name('import.finalize');
        Route::post('/import/{batch}/rollback', [StudentStudyAdministrationController::class, 'rollbackImportBatch'])->name('import.rollback');
        Route::get('/import/{batch}/export-errors', [StudentStudyAdministrationController::class, 'exportImportErrors'])->name('import.export-errors');
        Route::get('/import/{batch}/export-results', [StudentStudyAdministrationController::class, 'exportImportResults'])->name('import.export-results');
        Route::get('/batches', [StudentStudyAdministrationController::class, 'batchHistory'])->name('batches');
        Route::get('/batches/{source}/{id}', [StudentStudyAdministrationController::class, 'showBatch'])->name('batches.show');
    });

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

    Route::prefix('aktor-akademik')->name('aktor-akademik.')->group(function () {
        Route::get('/', [AktorAkademikController::class, 'index'])->name('index');
        Route::get('/kaprodi', [AktorAkademikController::class, 'kaprodi'])->name('kaprodi');
        Route::get('/pembimbing-akademik', [AktorAkademikController::class, 'pembimbingAkademik'])->name('pembimbing-akademik');
        Route::get('/baak', [AktorAkademikController::class, 'baak'])->name('baak');
    });

    Route::prefix('prodi')->name('prodi.')->group(function () {
        Route::get('/', [ProdiController::class, 'index'])->name('index');
        Route::post('/', [ProdiController::class, 'store'])->name('store');
        Route::get('/{id}', [ProdiController::class, 'show'])->name('show');
        Route::put('/{id}', [ProdiController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProdiController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/kaprodi', [ProdiController::class, 'updateKaprodi'])->name('updateKaprodi');
    });


    Route::prefix('capaian')->name('capaian.')->group(function () {
        Route::get('/prodi', [CapaianController::class, 'indexProdi'])->name('indexProdi');
        Route::get('/data', [CapaianController::class, 'getDataProdi'])->name('dataProdi');
        Route::get('/prodi/{id_prodi}', [CapaianController::class, 'detailProdi'])->name('detailProdi');
        Route::prefix('pl')->name('pl.')->group(function () {
            Route::get('{id_prodi}', [ProfileLulusanController::class, 'index'])->name('index');
            Route::get('/data/{id_prodi}', [ProfileLulusanController::class, 'getData'])->name('data');
            Route::post('/{id_prodi}', [ProfileLulusanController::class, 'store'])->name('store');
            Route::get('/{id}', [ProfileLulusanController::class, 'show'])->name('show');
            Route::put('/{id}/{id_prodi}', [ProfileLulusanController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProfileLulusanController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('cpl')->name('cpl.')->group(function () {
            Route::get('{id_prodi}', [CPLController::class, 'index'])->name('index');
            Route::get('/data/{id_prodi}', [CPLController::class, 'getData'])->name('data');
            Route::post('/{id_prodi}', [CPLController::class, 'store'])->name('store');
            Route::get('/{id}', [CPLController::class, 'show'])->name('show');
            Route::put('/{id}/{id_prodi}', [CPLController::class, 'update'])->name('update');
            Route::delete('/{id}', [CPLController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('indikator-kinerja')->name('indikator-kinerja.')->group(function () {
            Route::post('/{id_cpl}', [CPLController::class, 'storeIndikatorKinerja'])->name('store');
            Route::put('/{id}', [CPLController::class, 'updateIndikatorKinerja'])->name('update');
            Route::delete('/{id}', [CPLController::class, 'destroyIndikatorKinerja'])->name('destroy');
        });
        Route::prefix('pl-cpl')->name('pl-cpl.')->group(function () {
            Route::get('{id_prodi}', [MappingPLCPLController::class, 'index'])->name('index');
            Route::get('/data/{id_prodi}', [MappingPLCPLController::class, 'getData'])->name('data');
            Route::post('/', [MappingPLCPLController::class, 'store'])->name('store');
        });
        Route::prefix('cpl-mk')->name('cpl-mk.')->group(function () {
            Route::get('{id_prodi}', [MappingCPLMKController::class, 'index'])->name('index');
            Route::get('/data/{id_prodi}', [MappingCPLMKController::class, 'getData'])->name('data');
            Route::post('/', [MappingCPLMKController::class, 'store'])->name('store');
        });
    });

    // Route Ruang Kuliah
    Route::prefix('ruang-kuliah')->name('ruang-kuliah.')->group(function () {
        Route::get('/', [RuangKuliahController::class, 'index'])->name('index');
        Route::get('/create', [RuangKuliahController::class, 'create'])->name('create');
        Route::post('/', [RuangKuliahController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RuangKuliahController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RuangKuliahController::class, 'update'])->name('update');
        Route::delete('/{id}', [RuangKuliahController::class, 'destroy'])->name('destroy');
    });

    // Route Periode KRS
    Route::prefix('periode-krs')->name('periode-krs.')->group(function () {
        Route::get('/', [PeriodeKRSController::class, 'index'])->name('index');
        Route::get('/create', [PeriodeKRSController::class, 'create'])->name('create');
        Route::post('/', [PeriodeKRSController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PeriodeKRSController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PeriodeKRSController::class, 'update'])->name('update');
        Route::delete('/{id}', [PeriodeKRSController::class, 'destroy'])->name('destroy');
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
        Route::get('/{id}/prasyarat', [MataKuliahController::class, 'prasyarat'])->name('prasyarat');
        Route::put('/{id}/prasyarat', [MataKuliahController::class, 'updatePrasyarat'])->name('prasyarat.update');
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
        Route::get('/{id}/mata-kuliah-json', [KurikulumController::class, 'getMataKuliahByKurikulum'])->name('mata-kuliah-json');
        Route::get('/edit-kolektif/{id}', [KurikulumController::class, 'editkolektif'])->name('edit-kolektif');
        Route::put('/{id}', [KurikulumController::class, 'update'])->name('update');
        Route::delete('/{id}', [KurikulumController::class, 'destroy'])->name('destroy');
        Route::post('/konversi-mata-kuliah', [KurikulumController::class, 'storeKonversiMataKuliah'])->name('konversi-mata-kuliah.store');
        Route::put('/konversi-mata-kuliah/{id}', [KurikulumController::class, 'updateKonversiMataKuliah'])->name('konversi-mata-kuliah.update');
        Route::delete('/konversi-mata-kuliah/{id}', [KurikulumController::class, 'destroyKonversiMataKuliah'])->name('konversi-mata-kuliah.destroy');

        // Tambahkan route tambahan untuk manajemen mata kuliah
        Route::post('/{id}/tambah-mata-kuliah', [KurikulumController::class, 'tambahMataKuliahManual'])->name('tambah-mata-kuliah');
        Route::post('/{id}/tambah-mata-kuliah-checkbox', [KurikulumController::class, 'tambahMataKuliahManualcheckbox'])->name('tambah-mata-kuliah-checkbox');
        Route::post('/{id_tujuan}/clone-mata-kuliah', [KurikulumController::class, 'cloneMataKuliah'])->name('clone-mata-kuliah');
        Route::put('/{id}/mata-kuliah/{id_mk}', [KurikulumController::class, 'updateMataKuliah'])->name('update-mata-kuliah');
        Route::delete('/{id}/mata-kuliah/{id_mk}', [KurikulumController::class, 'hapusMataKuliah'])->name('hapus-mata-kuliah');
    });

    Route::prefix('kelas-kuliah')->name('kelas-kuliah.')->group(function () {
        Route::get('/data', [KelaskuliahController::class, 'getDatakelaskuliah'])->name('dataKelaskuliah');
        Route::get('/', [KelaskuliahController::class, 'index'])->name('index');
        Route::get('/add', [KelaskuliahController::class, 'create'])->name('create');
        Route::post('/', [KelaskuliahController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [KelaskuliahController::class, 'detail'])->name('detail');
        Route::post('/detail/{id}/register-krs', [KelaskuliahController::class, 'registerKrsMahasiswa'])->name('register-krs');
        Route::put('/{id}', [KelaskuliahController::class, 'update'])->name('update');
        Route::delete('/{id}', [KelaskuliahController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/tab/{tab}', [KelaskuliahController::class, 'tab'])->name('tab');
    });

    Route::prefix('dosen-pengajar-kelas')->name('dosen-pengajar-kelas.')->group(function () {
        Route::get('/kelas/{id_kelas_kuliah}', [DosenPengajarKelasController::class, 'index'])->name('index');
        Route::post('/kelas/{id_kelas_kuliah}', [DosenPengajarKelasController::class, 'store'])->name('store');
        Route::get('/{id}', [DosenPengajarKelasController::class, 'show'])->name('show');
        Route::put('/{id}', [DosenPengajarKelasController::class, 'update'])->name('update');
        Route::delete('/{id}', [DosenPengajarKelasController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('jadwal-kelas')->name('jadwal-kelas.')->group(function () {
        Route::get('/kelas/{id_kelas_kuliah}', [MasterDataJadwalController::class, 'index'])->name('index');
        Route::post('/kelas/{id_kelas_kuliah}', [MasterDataJadwalController::class, 'store'])->name('store');
        Route::get('/{id}', [MasterDataJadwalController::class, 'show'])->name('show');
        Route::put('/{id}', [MasterDataJadwalController::class, 'update'])->name('update');
        Route::delete('/{id}', [MasterDataJadwalController::class, 'destroy'])->name('destroy');
    });

    // Route Dosen
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/', [DosenController::class, 'index'])->name('index');
        Route::post('/', [DosenController::class, 'store'])->name('store');
        Route::get('/{id}', [DosenController::class, 'show'])->whereUuid('id')->name('show');
        Route::put('/{id}', [DosenController::class, 'update'])->whereUuid('id')->name('update');
        Route::delete('/{id}', [DosenController::class, 'destroy'])->whereUuid('id')->name('destroy');
    });

    Route::prefix('dosen-wali')->name('dosen-wali.')->group(function () {
        Route::get('/data', [DosenWaliController::class, 'getDataDosenWali'])->name('getDataDosenWali');
        Route::get('/', [DosenWaliController::class, 'index'])->name('index');
        Route::get('/add', [DosenWaliController::class, 'create'])->name('create');
        Route::get('/search-mahasiswa', [DosenWaliController::class, 'searchMahasiswa'])->name('search-mahasiswa');
        Route::post('/assign', [DosenWaliController::class, 'assign'])->name('assign');
        Route::post('/transfer', [DosenWaliController::class, 'transfer'])->name('transfer');
        Route::post('/unassign', [DosenWaliController::class, 'unassign'])->name('unassign');
        Route::post('/remove', [DosenWaliController::class, 'remove'])->name('remove');
        Route::get('/{id}', [DosenWaliController::class, 'detail'])->name('detail');
        // Route::delete('/{id}', [DosenWaliController::class, 'destroy'])->name('destroy');
    });

    // Route Mahasiswa
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/', [MahasiswaController::class, 'index'])->name('index');
        Route::post('/', [MahasiswaController::class, 'store'])->name('store');
        Route::post('/bulk-delete', [MahasiswaController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{id}', [MahasiswaController::class, 'show'])->whereUuid('id')->name('show');
        Route::put('/{id}', [MahasiswaController::class, 'update'])->whereUuid('id')->name('update');
        Route::delete('/{id}', [MahasiswaController::class, 'destroy'])->whereUuid('id')->name('destroy');

        // Import/Export Routes
        Route::post('/import/{id_prodi}', [MahasiswaController::class, 'import'])->name('import');
        Route::get('/export/template/{id_prodi}', [MahasiswaController::class, 'exportTemplate'])->name('export.template');
    });

    // Route Mahasiswa Baru
    Route::prefix('mahasiswa-baru')->name('mahasiswa.baru.')->group(function () {
        Route::get('/', [MahasiswaBaruController::class, 'index'])->name('index');
        Route::post('/', [MahasiswaBaruController::class, 'store'])->name('store');
        Route::post('/sync', [MahasiswaBaruController::class, 'sync'])->name('sync');
        Route::get('/{id}', [MahasiswaBaruController::class, 'show'])->whereUuid('id')->name('show');
        Route::put('/{id}', [MahasiswaBaruController::class, 'update'])->whereUuid('id')->name('update');
        Route::delete('/{id}', [MahasiswaBaruController::class, 'destroy'])->whereUuid('id')->name('destroy');
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

    // Route Profile Dosen
    Route::get('/profile-dosen', [ProfileDosenController::class, 'index'])->name('profile-dosen.index');
    Route::post('/profile-dosen', [ProfileDosenController::class, 'store'])->name('profile-dosen.store');
    Route::get('/profile-dosen/{id}', [ProfileDosenController::class, 'show'])->name('profile-dosen.show');
    Route::put('/profile-dosen/{id}', [ProfileDosenController::class, 'update'])->name('profile-dosen.update');
    Route::delete('/profile-dosen/{id}', [ProfileDosenController::class, 'destroy'])->name('profile-dosen.destroy');

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

    // Route PMB Pendaftaran
    Route::get('/pmb-pendaftaran', [PmbPendaftaranController::class, 'index'])->name('pmb-pendaftaran.index');
    Route::post('/pmb-pendaftaran', [PmbPendaftaranController::class, 'store'])->name('pmb-pendaftaran.store');
    Route::get('/pmb-pendaftaran/{id?}', [PmbPendaftaranController::class, 'show'])->name('pmb-pendaftaran.show');
    Route::put('/pmb-pendaftaran/{id?}', [PmbPendaftaranController::class, 'update'])->name('pmb-pendaftaran.update');
    Route::delete('/pmb-pendaftaran/{id?}', [PmbPendaftaranController::class, 'destroy'])->name('pmb-pendaftaran.destroy');

    // Route Sertifikat Akreditasi
    Route::get('/sertifikat-akreditasi', [SertifikatAkreditasi::class, 'index'])->name('sertifikat-akreditasi.index');
    Route::post('/sertifikat-akreditasi', [SertifikatAkreditasi::class, 'store'])->name('sertifikat-akreditasi.store');
    Route::get('/sertifikat-akreditasi/{id}', [SertifikatAkreditasi::class, 'show'])->name('sertifikat-akreditasi.show');
    Route::put('/sertifikat-akreditasi/{id}', [SertifikatAkreditasi::class, 'update'])->name('sertifikat-akreditasi.update');
    Route::delete('/sertifikat-akreditasi/{id}', [SertifikatAkreditasi::class, 'destroy'])->name('sertifikat-akreditasi.destroy');

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

    Route::prefix('mahasiswa/khs')->name('student.khs.')->group(function () {
        Route::get('/', [KHSController::class, 'index'])->name('index');
        Route::get('/data', [KHSController::class, 'data'])->name('data');
        Route::get('/{khsId}/download', [KHSController::class, 'download'])->name('download');
        Route::get('/{khsId}/print', [KHSController::class, 'print'])->name('print');
        Route::get('/{khsId}', [KHSController::class, 'show'])->name('show');
    });

    Route::prefix('mahasiswa/transkrip')->name('student.transkrip.')->group(function () {
        Route::get('/', [TranskripController::class, 'index'])->name('index');
        Route::get('/data', [TranskripController::class, 'data'])->name('data');
        Route::get('/{transkripId}', [TranskripController::class, 'show'])->name('show');
    });


    Route::get('/dosen-pa/krs', [KRSDosenWaliController::class, 'index'])->name('dosenpa.krs.index');
    Route::get('/dosen-pa/krs/statistics', [KRSDosenWaliController::class, 'statistics'])->name('dosenpa.krs.statistics');
    Route::get('/dosen-pa/krs/pending', [KRSDosenWaliController::class, 'pending'])->name('dosenpa.krs.pending');
    Route::get('/dosen-pa/krs/{id}', [KRSDosenWaliController::class, 'show'])->name('dosenpa.krs.show');
    Route::post('/dosen-pa/krs/approve', [KRSDosenWaliController::class, 'approve'])->name('dosenpa.krs.approve');
    Route::post('/dosen-pa/krs/revision', [KRSDosenWaliController::class, 'revision'])->name('dosenpa.krs.revision');
    Route::post('/dosen-pa/krs/reject', [KRSDosenWaliController::class, 'reject'])->name('dosenpa.krs.reject');

    Route::get('/krs/current', [KRSMahasiswaController::class, 'current'])->name('krs.current');
    Route::post('/krs/current/init', [KRSMahasiswaController::class, 'initCurrent'])->name('krs.current.init');
    Route::post('/krs', [KRSMahasiswaController::class, 'store'])->name('krs.store');
    Route::get('/krs/penawaran', [KRSMahasiswaController::class, 'penawaranMK'])->name('krs.penawaran');
    Route::get('/krs/repeat-candidates', [KRSMahasiswaController::class, 'repeatCandidates'])->name('krs.repeat-candidates');
    Route::get('/krs/data', [KRSMahasiswaController::class, 'dataKrs'])->name('krs.data');
    Route::get('/krs/statistics', [KRSMahasiswaController::class, 'statistics'])->name('krs.statistics');
    Route::get('/krs/validation-summary', [KRSMahasiswaController::class, 'validationSummary'])->name('krs.validation-summary');
    Route::post('/krs/add-mata-kuliah', [KRSMahasiswaController::class, 'addMataKuliah'])->name('krs.add-mata-kuliah');
    Route::post('/krs/submit', [KRSMahasiswaController::class, 'submit'])->name('krs.submit');
    Route::delete('/krs/{krsId}/remove-mata-kuliah/{kelasKuliahId}', [KRSMahasiswaController::class, 'removeMataKuliah'])->name('krs.remove-mata-kuliah');
    Route::get('/krs/{id}/print', [KRSMahasiswaController::class, 'print'])->name('krs.print');
    Route::get('/krs/{id}', [KRSMahasiswaController::class, 'show'])->name('krs.show');
    Route::get('/krs', [KRSMahasiswaController::class, 'index'])->name('krs.index');

    Route::prefix('dosen/pertemuan-presensi')->name('dosen.pertemuan-presensi.')->group(function () {
        Route::get('/', [PertemuanPresensiController::class, 'index'])->name('index');
        Route::get('/kelas-kuliah', [PertemuanPresensiController::class, 'kelasKuliah'])->name('kelas.index');
        Route::get('/kelas/{kelasKuliahId}/pertemuan', [PertemuanPresensiController::class, 'pertemuanByKelas'])->name('pertemuan.index');
        Route::post('/kelas/{kelasKuliahId}/pertemuan', [PertemuanPresensiController::class, 'storePertemuan'])->name('pertemuan.store');
        Route::put('/pertemuan/{id}', [PertemuanPresensiController::class, 'updatePertemuan'])->name('pertemuan.update');
        Route::get('/pertemuan/{pertemuanId}/presensi', [PertemuanPresensiController::class, 'presensiByPertemuan'])->name('presensi.show');
        Route::post('/pertemuan/{pertemuanId}/presensi/generate-peserta', [PertemuanPresensiController::class, 'generatePeserta'])->name('presensi.generate');
        Route::put('/pertemuan/{pertemuanId}/presensi', [PertemuanPresensiController::class, 'updatePresensi'])->name('presensi.update');
        Route::get('/kelas/{kelasKuliahId}/rekap', [PertemuanPresensiController::class, 'rekapByKelas'])->name('rekap');
    });

    Route::prefix('dosen/penilaian')->name('dosen.penilaian.')->group(function () {
        Route::get('/', [PenilaianController::class, 'index'])->name('index');
        Route::get('/kelas-kuliah', [PenilaianController::class, 'kelasKuliah'])->name('kelas.index');
        Route::get('/kelas/{kelasKuliahId}/komponen', [PenilaianController::class, 'komponenByKelas'])->name('komponen.index');
        Route::post('/kelas/{kelasKuliahId}/komponen', [PenilaianController::class, 'storeKomponen'])->name('komponen.store');
        Route::put('/komponen/{id}', [PenilaianController::class, 'updateKomponen'])->name('komponen.update');
        Route::delete('/komponen/{id}', [PenilaianController::class, 'destroyKomponen'])->name('komponen.destroy');
        Route::get('/kelas/{kelasKuliahId}/nilai', [PenilaianController::class, 'nilaiByKelas'])->name('nilai.index');
        Route::put('/komponen/{komponenId}/nilai', [PenilaianController::class, 'updateNilaiKomponen'])->name('nilai.update');
        Route::post('/kelas/{kelasKuliahId}/publish-final', [PenilaianController::class, 'publishFinal'])->name('publish-final');
        Route::post('/kelas/{kelasKuliahId}/reopen', [PenilaianController::class, 'reopen'])->name('reopen');
        Route::put('/krs-detail/{krsDetailId}/manual-final', [PenilaianController::class, 'manualFinal'])->name('manual-final');
    });

    Route::prefix('wisuda')->name('wisuda.')->group(function () {
        Route::get('/periode', [WisudaController::class, 'indexPeriode'])->name('periode.index');
        Route::get('/periode/{id}', [WisudaController::class, 'showPeriode'])->name('periode.show');
        Route::post('/periode', [WisudaController::class, 'storePeriode'])->name('periode.store');
        Route::put('/periode/{id}', [WisudaController::class, 'updatePeriode'])->name('periode.update');

        Route::get('/periode/{periodeId}/peserta', [WisudaController::class, 'peserta'])->name('peserta.index');
        Route::get('/peserta/{id}', [WisudaController::class, 'showPeserta'])->name('peserta.show');
        Route::post('/periode/{periodeId}/peserta', [WisudaController::class, 'storePeserta'])->name('peserta.store');
        Route::put('/peserta/{id}', [WisudaController::class, 'updatePeserta'])->name('peserta.update');
    });

    Route::prefix('yudisium')->name('yudisium.')->group(function () {
        Route::get('/', [YudisiumController::class, 'index'])->name('index');
        Route::get('/{id}', [YudisiumController::class, 'show'])->name('show');
        Route::get('/preview/mahasiswa', [YudisiumController::class, 'preview'])->name('preview');
        Route::post('/generate', [YudisiumController::class, 'generate'])->name('generate');
    });

    Route::get('/akhir-studi/monitoring', [MonitoringAkhirStudiController::class, 'index'])->name('akhir-studi.monitoring');
    Route::get('/akademik/monitoring', [MonitoringAkademikController::class, 'index'])->name('akademik.monitoring');

    Route::prefix('kelulusan')->name('kelulusan.')->group(function () {
        Route::get('/', [KelulusanController::class, 'index'])->name('index');
        Route::get('/{id}', [KelulusanController::class, 'show'])->name('show');
        Route::post('/generate', [KelulusanController::class, 'generate'])->name('generate');
    });

    Route::prefix('tugas-akhir')->name('tugas-akhir.')->group(function () {
        Route::get('/', [TugasAkhirController::class, 'index'])->name('index');
        Route::get('/{id}', [TugasAkhirController::class, 'show'])->name('show');
        Route::post('/', [TugasAkhirController::class, 'store'])->name('store');
        Route::put('/{id}', [TugasAkhirController::class, 'update'])->name('update');
        Route::put('/{id}/pembimbing', [TugasAkhirController::class, 'syncPembimbing'])->name('sync-pembimbing');
        Route::post('/{id}/ujian', [TugasAkhirController::class, 'storeUjian'])->name('store-ujian');
        Route::put('/ujian/{id}', [TugasAkhirController::class, 'updateUjian'])->name('update-ujian');
    });
});
