<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman laporan (Tabel)
     */
    public function index()
    {
        // Ambil data peminjaman, urutkan dari yang terbaru
        // Gunakan 'with' untuk mengambil data member & buku sekaligus (Eager Loading)
        $loans = Loan::with(['member', 'book'])
            ->latest()
            ->paginate(10);

        return view('dasbor.laporan.index', compact('loans'));
    }

    /**
     * Export ke PDF
     */
    public function export()
    {
        // Ambil semua data untuk dicetak
        $loans = Loan::with(['member', 'book'])
            ->latest()
            ->get();

        // Load view PDF
        $pdf = Pdf::loadView('dasbor.laporan.pdf', compact('loans'));

        // Set ukuran kertas Landscape agar tabel muat
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Sirkulasi-Perpustakaan.pdf');
    }
}
