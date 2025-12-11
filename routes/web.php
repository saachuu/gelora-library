<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CirculationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitController; // <--- WAJIB ADA AGAR TIDAK ERROR

// Rute Publik
Route::get('/', function () {
    return redirect('/dasbor');
});

// Rute yang memerlukan autentikasi
Route::middleware(['auth', 'verified'])->group(function () {

    // Group Dasbor
    Route::prefix('dasbor')->group(function() {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Resourceful Routes
        Route::resource('/buku', BookController::class)->names('dasbor.buku');
        Route::resource('/anggota', MemberController::class)->names('dasbor.anggota');
        Route::resource('/kategori', \App\Http\Controllers\CategoryController::class)->names('dasbor.kategori')->parameters(['kategori' => 'category']);

        // === API LIVE SEARCH SIRKULASI ===
        Route::get('/api/members/search', [CirculationController::class, 'searchMembers'])->name('api.members.search');
        Route::get('/api/books/search', [CirculationController::class, 'searchBooks'])->name('api.books.search');

        // === API LIVE SEARCH ABSENSI (Baru) ===
        Route::get('/api/absensi/search', [VisitController::class, 'searchMember'])->name('api.absensi.search');

        // Rute Sirkulasi
        Route::get('/sirkulasi', [CirculationController::class, 'index'])->name('dasbor.sirkulasi.index');
        Route::post('/sirkulasi/pinjam', [CirculationController::class, 'storeLoan'])->name('dasbor.sirkulasi.pinjam');
        Route::post('/sirkulasi/kembali', [CirculationController::class, 'storeReturn'])->name('dasbor.sirkulasi.kembali');

        // Rute Absensi / Buku Tamu
        Route::get('/absensi', [VisitController::class, 'index'])->name('dasbor.absensi.index');
        Route::post('/absensi', [VisitController::class, 'store'])->name('dasbor.absensi.store');
        // Rute Peringkat Siswa
        Route::get('/absensi/peringkat', [VisitController::class, 'leaderboard'])->name('dasbor.absensi.leaderboard');
        // Rute Cetak PDF Keaktifan Siswa
        Route::get('/absensi/export-pdf', [VisitController::class, 'exportPdf'])->name('dasbor.absensi.pdf');
        // ==========================================
        Route::get('/api/absensi/status/{memberId}', [VisitController::class, 'checkStatus'])->name('api.absensi.status');

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
