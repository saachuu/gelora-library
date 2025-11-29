<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CirculationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

// Rute Publik
Route::get('/', function () {
    return redirect('/dasbor');
});


// Rute yang memerlukan autentikasi
Route::middleware(['auth', 'verified'])->group(function () {
    // Rute Dasbor Admin
    Route::prefix('dasbor')->group(function() {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Resourceful Routes untuk CRUD
        Route::resource('/buku', BookController::class)->names('dasbor.buku');
        Route::resource('/anggota', MemberController::class)->names('dasbor.anggota');
        Route::resource('/kategori', \App\Http\Controllers\CategoryController::class)->names('dasbor.kategori');

        // Rute Sirkulasi
        Route::get('/sirkulasi', [CirculationController::class, 'index'])->name('dasbor.sirkulasi.index');
        Route::post('/sirkulasi/pinjam', [CirculationController::class, 'storeLoan'])->name('dasbor.sirkulasi.pinjam');
        Route::post('/sirkulasi/kembali', [CirculationController::class, 'storeReturn'])->name('dasbor.sirkulasi.kembali');

        // Rute Laporan
        Route::get('/laporan/export', [ReportController::class, 'export'])->name('dasbor.laporan.export');
        Route::get('/laporan', [ReportController::class, 'index'])->name('dasbor.laporan.index');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';