<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Visit; // <--- Jangan lupa import Model Visit
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk query raw

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama
        $bookCount = Book::count();
        $memberCount = Member::where('is_active', true)->count();
        $activeLoanCount = Loan::where('status', 'Dipinjam')->count();

        // Pengunjung Hari Ini (Fitur Baru)
        $todayVisitCount = Visit::whereDate('created_at', today())->count();

        $overdueLoanCount = Loan::where('status', 'Terlambat')
                                 ->orWhere(function($query) {
                                     $query->where('status', 'Dipinjam')
                                           ->where('due_date', '<', now());
                                 })
                                 ->count();

        // 2. Grafik Peminjaman Bulanan (Tetap sama)
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $monthFunction = $isSqlite ? "strftime('%m', created_at)" : 'MONTH(created_at)';

        $monthlyCounts = Loan::selectRaw("$monthFunction as month, COUNT(*) as count")
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $key = $isSqlite ? sprintf('%02d', $i) : $i;
            $count = $monthlyCounts[$key] ?? ($monthlyCounts[(int)$key] ?? 0);
            $monthlyData[] = $count;
        }

        // 3. Top 5 Siswa Terrajin (Berdasarkan Poin Kehadiran)
        // Kita ambil member yang punya poin > 0, diurutkan dari yang terbanyak
        $topMembers = Member::withCount(['visits as total_points' => function ($query) {
                $query->where('got_point', true);
            }])
            ->orderByDesc('total_points')
            ->take(5)
            ->get();

        // 4. Buku Populer & Aktivitas Terbaru (Tetap sama)
        $popularBooks = Book::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->take(5)
            ->get();

        $recentActivities = Loan::with(['member', 'book'])
            ->latest()
            ->take(5)
            ->get();

        // 5. Statistik Kategori (Tetap sama)
        $categoryStats = \App\Models\Category::withCount('books')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->books_count,
                ];
            });

        return view('dasbor.index', compact(
            'bookCount',
            'memberCount',
            'activeLoanCount',
            'overdueLoanCount',
            'todayVisitCount', // <--- Variabel baru
            'monthlyData',
            'popularBooks',
            'recentActivities',
            'categoryStats',
            'topMembers'       // <--- Variabel baru
        ));
    }
}
