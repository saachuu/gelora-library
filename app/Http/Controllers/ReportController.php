<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
{
    /**
     * Menampilkan daftar laporan peminjaman.
     */
    public function index(Request $request)
    {
        // Set tanggal default ke bulan ini jika tidak ada input
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Ambil data peminjaman berdasarkan rentang tanggal
        $loans = Loan::with(['book', 'member'])
            ->whereBetween('loan_date', [$startDate, $endDate])
            ->orderBy('loan_date', 'desc')
            ->get();

        return view('dasbor.laporan.index', compact('loans', 'startDate', 'endDate'));
    }

    /**
     * Mengekspor laporan peminjaman ke PDF.
     */
    public function export(Request $request)
    {
        // Validasi dan set tanggal default
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Ambil data peminjaman berdasarkan rentang tanggal
        $loans = Loan::with(['book', 'member'])
            ->whereBetween('loan_date', [$startDate, $endDate])
            ->orderBy('loan_date', 'desc')
            ->get();

        // Data untuk dilempar ke view PDF
        $data = [
            'loans' => $loans,
            'startDate' => Carbon::parse($startDate)->isoFormat('D MMMM Y'),
            'endDate' => Carbon::parse($endDate)->isoFormat('D MMMM Y')
        ];

        // Generate PDF
        $pdf = PDF::loadView('dasbor.laporan.pdf', $data);
        
        // Download PDF
        $fileName = 'laporan-peminjaman-' . $startDate . '-' . $endDate . '.pdf';
        return $pdf->download($fileName);
    }
}