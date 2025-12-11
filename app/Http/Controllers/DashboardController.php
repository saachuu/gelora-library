<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Visit; // Tambahkan Model Visit
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- KARTU ATAS (Total Data) ---
        $total_buku = Book::count();
        $total_anggota = Member::count();
        $total_peminjaman = Loan::whereIn('status', ['Dipinjam', 'Terlambat'])->count();
        $total_dikembalikan = Loan::where('status', 'Dikembalikan')->count();

        // --- CHART 1: BUKU TERPOPULER (Top 5) ---
        $populer = Loan::select('book_id', DB::raw('count(*) as total'))
            ->groupBy('book_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('book')
            ->get();

        $label_populer = $populer->map(function($p) {
            return substr($p->book->title, 0, 20) . '...';
        });
        $data_populer = $populer->pluck('total');

        // --- CHART 2: TREN PEMINJAMAN (6 Bulan Terakhir) ---
        $peminjaman_bulanan = Loan::select(
            DB::raw('DATE_FORMAT(loan_date, "%Y-%m") as bulan'),
            DB::raw('count(*) as total')
        )
        ->where('loan_date', '>=', Carbon::now()->subMonths(6))
        ->groupBy('bulan')
        ->orderBy('bulan', 'asc')
        ->get();

        $label_tren = $peminjaman_bulanan->map(function($item) {
            return Carbon::createFromFormat('Y-m', $item->bulan)->format('M Y');
        });
        $data_tren = $peminjaman_bulanan->pluck('total');

        // --- CHART 3: STATISTIK KATEGORI ---
        $kategori = Book::select('category_id', DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $label_kategori = $kategori->map(fn($k) => $k->category->name ?? 'Lainnya');
        $data_kategori = $kategori->pluck('total');

        // --- TAMBAHAN BARU: TOP 3 SISWA RAJIN (POIN TERTINGGI) ---
        $top_siswa = Visit::where('got_point', true)
            ->select('member_id', DB::raw('count(*) as total_poin'))
            ->groupBy('member_id')
            ->orderByDesc('total_poin')
            ->limit(3)
            ->with('member')
            ->get();

        return view('dasbor.index', compact(
            'total_buku',
            'total_anggota',
            'total_peminjaman',
            'total_dikembalikan',
            'label_populer', 'data_populer',
            'label_tren', 'data_tren',
            'label_kategori', 'data_kategori',
            'top_siswa' // Kirim data siswa ke view
        ));
    }
}
