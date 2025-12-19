<?php
use App\Http\Controllers\Rkpd\TahapPenjadwalanController;
use App\Http\Controllers\Rkpd\SubTahapController;
use App\Http\Controllers\Rkpd\JadwalController;
use App\Http\Controllers\Rkpd\RenjaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('rkpd')->group(function () {
    // tahap penjadwalan
    Route::get('/tahap-penjadwalan', [TahapPenjadwalanController::class, 'index'])->name('rkpd.tahap-penjadwalan.index');
    Route::post('/tahap-penjadwalan', [TahapPenjadwalanController::class, 'store'])->name('tahap-penjadwalan.store');
    Route::get('/tahap-penjadwalan/{id}/edit', [TahapPenjadwalanController::class, 'edit'])->name('tahap-penjadwalan.edit');
    Route::put('/tahap-penjadwalan/{id}', [TahapPenjadwalanController::class, 'update'])->name('tahap-penjadwalan.update');
    Route::delete('/tahap-penjadwalan/{id}', [TahapPenjadwalanController::class, 'destroy'])->name('tahap-penjadwalan.destroy');
    Route::post('/tahap-penjadwalan/bulk-delete', [TahapPenjadwalanController::class, 'bulkDelete'])->name('tahap-penjadwalan.bulk-delete');

    // Sub Tahap
    Route::get('/sub-tahap', [SubTahapController::class, 'index'])->name('rkpd.sub-tahap.index');
    Route::post('/sub-tahap', [SubTahapController::class, 'store'])->name('rkpd.sub-tahap.store');
    Route::get('/sub-tahap/{id}/edit', [SubTahapController::class, 'edit'])->name('rkpd.sub-tahap.edit');
    Route::put('/sub-tahap/{id}', [SubTahapController::class, 'update'])->name('rkpd.sub-tahap.update');
    Route::delete('/sub-tahap/{id}', [SubTahapController::class, 'destroy'])->name('rkpd.sub-tahap.destroy');
    Route::post('/sub-tahap/bulk-delete', [SubTahapController::class, 'bulkDelete'])->name('rkpd.sub-tahap.bulk-delete');

    // Jadwal RKPD
    Route::get('/jadwal-rkpd', [JadwalController::class, 'index'])->name('rkpd.jadwal-rkpd.index');
    Route::post('/jadwal-rkpd', [JadwalController::class, 'store'])->name('rkpd.jadwal-rkpd.store');
    Route::get('/jadwal-rkpd/{id}/edit', [JadwalController::class, 'edit'])->name('rkpd.jadwal-rkpd.edit');
    Route::put('/jadwal-rkpd/{id}', [JadwalController::class, 'update'])->name('rkpd.jadwal-rkpd.update');
    Route::delete('/jadwal-rkpd/{id}', [JadwalController::class, 'destroy'])->name('rkpd.jadwal-rkpd.destroy');
    Route::post('/jadwal-rkpd/bulk-delete', [JadwalController::class, 'bulkDelete'])->name('rkpd.jadwal-rkpd.bulk-delete');

    // Renja
    Route::get('/renja', [RenjaController::class, 'index'])->name('rkpd.renja.index');
    Route::get('/renja/sub-kegiatan', [RenjaController::class, 'getSubKegiatanBySkpd'])->name('sub-kegiatan');
    Route::post('/rkpd/renja/store', [RenjaController::class, 'store'])->name('renja.store');
    Route::get('renja/data', [RenjaController::class, 'getData'])->name('renja.data');
     Route::get('/renja/{id}/rincian', [RenjaController::class, 'showRincian'])->name('renja.rincian');

     // ✅ RINCIAN BELANJA
    Route::get('/rincian/get-akun', [RenjaController::class, 'getAkunByJenisBelanja'])->name('rincian.get-akun');
    Route::get('/rincian/get-detail-akun', [RenjaController::class, 'getDetailAkun'])->name('rincian.get-detail-akun');
    Route::post('/rincian/store', [RenjaController::class, 'storerincian'])->name('rincian.store');
    Route::put('/rincian/update/{id}', [RenjaController::class, 'updateRincian'])->name('rincian.update');
    Route::delete('/rincian/delete/{id}', [RenjaController::class, 'destroyRincian'])->name('rincian.delete');
     
    Route::get('/paket-belanja/list', [RenjaController::class, 'getPaketBelanjaList'])->name('paket.list');
    Route::post('/paket-belanja/store', [RenjaController::class, 'storePaketBelanja'])->name('paket.store');
    Route::get('/paket-belanja/detail/{id}', [RenjaController::class, 'getPaketBelanjaDetail'])->name('paket.detail');
    Route::put('/paket-belanja/update/{id}', [RenjaController::class, 'updatePaketBelanja'])->name('paket.update');
    Route::delete('/paket-belanja/delete/{id}', [RenjaController::class, 'deletePaketBelanja'])->name('paket.delete');

});

    