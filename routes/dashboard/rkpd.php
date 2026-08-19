<?php

use App\Http\Controllers\Rkpd\JadwalController;
use App\Http\Controllers\Rkpd\RenjaController;
use App\Http\Controllers\Rkpd\RincianBelanjaController;
use App\Http\Controllers\Rkpd\SubTahapController;
use App\Http\Controllers\Rkpd\TahapPenjadwalanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('rkpd')->group(function () {
    // ==================== TAHAP PENJADWALAN ====================
    Route::get('/tahap-penjadwalan', [TahapPenjadwalanController::class, 'index'])->name('rkpd.tahap-penjadwalan.index');
    Route::post('/tahap-penjadwalan', [TahapPenjadwalanController::class, 'store'])->name('tahap-penjadwalan.store');
    Route::get('/tahap-penjadwalan/{id}/edit', [TahapPenjadwalanController::class, 'edit'])->name('tahap-penjadwalan.edit');
    Route::put('/tahap-penjadwalan/{id}', [TahapPenjadwalanController::class, 'update'])->name('tahap-penjadwalan.update');
    Route::delete('/tahap-penjadwalan/{id}', [TahapPenjadwalanController::class, 'destroy'])->name('tahap-penjadwalan.destroy');
    Route::post('/tahap-penjadwalan/bulk-delete', [TahapPenjadwalanController::class, 'bulkDelete'])->name('tahap-penjadwalan.bulk-delete');

    // ==================== SUB TAHAP ====================
    Route::get('/sub-tahap', [SubTahapController::class, 'index'])->name('rkpd.sub-tahap.index');
    Route::post('/sub-tahap', [SubTahapController::class, 'store'])->name('rkpd.sub-tahap.store');
    Route::get('/sub-tahap/{id}/edit', [SubTahapController::class, 'edit'])->name('rkpd.sub-tahap.edit');
    Route::put('/sub-tahap/{id}', [SubTahapController::class, 'update'])->name('rkpd.sub-tahap.update');
    Route::delete('/sub-tahap/{id}', [SubTahapController::class, 'destroy'])->name('rkpd.sub-tahap.destroy');
    Route::post('/sub-tahap/bulk-delete', [SubTahapController::class, 'bulkDelete'])->name('rkpd.sub-tahap.bulk-delete');

    // ==================== JADWAL RKPD ====================
    Route::get('/jadwal-rkpd', [JadwalController::class, 'index'])->name('rkpd.jadwal-rkpd.index');
    Route::post('/jadwal-rkpd', [JadwalController::class, 'store'])->name('rkpd.jadwal-rkpd.store');
    Route::get('/jadwal-rkpd/{id}/edit', [JadwalController::class, 'edit'])->name('rkpd.jadwal-rkpd.edit');
    Route::put('/jadwal-rkpd/{id}', [JadwalController::class, 'update'])->name('rkpd.jadwal-rkpd.update');
    Route::delete('/jadwal-rkpd/{id}', [JadwalController::class, 'destroy'])->name('rkpd.jadwal-rkpd.destroy');
    Route::post('/jadwal-rkpd/bulk-delete', [JadwalController::class, 'bulkDelete'])->name('rkpd.jadwal-rkpd.bulk-delete');

    // ==================== RENJA (Sub Kegiatan) ====================
    Route::get('/renja', [RenjaController::class, 'index'])->name('rkpd.renja.index');
    Route::get('/renja/sub-kegiatan', [RenjaController::class, 'getSubKegiatanBySkpd'])->name('sub-kegiatan');
    Route::post('/rkpd/renja/store', [RenjaController::class, 'store'])->name('renja.store');
    Route::get('renja/data', [RenjaController::class, 'getData'])->name('renja.data');
    Route::get('/renja/{id}/edit', [RenjaController::class, 'edit'])->name('renja.edit');
    Route::put('/renja/{id}', [RenjaController::class, 'update'])->name('renja.update');
    Route::delete('/renja/{id}', [RenjaController::class, 'destroy'])->name('renja.destroy');
    Route::get('/renja/export-pdf/{id_skpd}', [RenjaController::class, 'exportPdf'])->name('renja.export-pdf');
    Route::get('/renja/{id}/cetak-rincian', [RenjaController::class, 'cetakRincian'])->name('renja.cetak-rincian');
    // View Rincian Belanja
    Route::get('/renja/{id}/rincian', [RincianBelanjaController::class, 'index'])->name('renja.rincian');
    // CRUD Rincian
    Route::post('/rincian/store', [RincianBelanjaController::class, 'storerincian'])->name('rincian.store');
    Route::put('/rincian/update/{id}', [RincianBelanjaController::class, 'updateRincian'])->name('rincian.update');
    Route::delete('/rincian/delete/{id}', [RincianBelanjaController::class, 'destroyRincian'])->name('rincian.delete');
    Route::get('/rincian/edit/{id}', [RincianBelanjaController::class, 'editRincian'])->name('rincian.edit');

    // Akun & Detail Akun
    Route::get('/rincian/get-akun', [RincianBelanjaController::class, 'getAkunByJenisBelanja'])->name('rincian.get-akun');
    Route::get('/rincian/get-detail-akun', [RincianBelanjaController::class, 'getDetailAkun'])->name('rincian.get-detail-akun');

    // SSH & Search Komponen
    Route::get('/rincian/get-ssh-data', [RincianBelanjaController::class, 'getSshData'])->name('rincian.get-ssh-data');
    Route::get('/rincian/search-komponen', [RincianBelanjaController::class, 'searchKomponen'])->name('rincian.search-komponen');

    // Paket Belanja
    Route::get('/paket-belanja/list', [RincianBelanjaController::class, 'getPaketBelanjaList'])->name('paket.list');
    Route::post('/paket-belanja/store', [RincianBelanjaController::class, 'storePaketBelanja'])->name('paket.store');
    Route::get('/paket-belanja/detail/{id}', [RincianBelanjaController::class, 'getPaketBelanjaDetail'])->name('paket.detail');
    Route::put('/paket-belanja/update/{id}', [RincianBelanjaController::class, 'updatePaketBelanja'])->name('paket.update');
    Route::delete('/paket-belanja/delete/{id}', [RincianBelanjaController::class, 'deletePaketBelanja'])->name('paket.delete');

    // Mintag (Kategori Belanja)
    Route::get('mintag/list', [RincianBelanjaController::class, 'getMintagList'])->name('mintag.list');
    Route::post('mintag/store', [RincianBelanjaController::class, 'storeMintag'])->name('mintag.store');
});
