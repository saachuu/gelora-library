<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookCount = Book::count();
        $memberCount = Member::where('is_active', true)->count();
        $activeLoanCount = Loan::where('status', 'Dipinjam')->count();
        $overdueLoanCount = Loan::where('status', 'Terlambat')
                                 ->orWhere(function($query) {
                                     $query->where('status', 'Dipinjam')
                                           ->where('due_date', '<', now());
                                 })
                                 ->count();

        // Analytics: Monthly Loans for current year
        $isSqlite = \DB::connection()->getDriverName() === 'sqlite';
        $monthFunction = $isSqlite ? "strftime('%m', created_at)" : 'MONTH(created_at)';
        
        $monthlyCounts = Loan::selectRaw("$monthFunction as month, COUNT(*) as count")
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            // SQLite returns month as string "01", "02", etc. MySQL might return int.
            // We cast to int to be safe.
            $key = $isSqlite ? sprintf('%02d', $i) : $i;
            // If the key isn't found, try the integer version (just in case)
            $count = $monthlyCounts[$key] ?? ($monthlyCounts[(int)$key] ?? 0);
            $monthlyData[] = $count;
        }

        // Popular Books (Top 5)
        $popularBooks = Book::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->take(5)
            ->get();

        // Recent Activities (Latest 5 loans)
        $recentActivities = Loan::with(['member', 'book'])
            ->latest()
            ->take(5)
            ->get();

        // Category Stats
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
            'monthlyData',
            'popularBooks',
            'recentActivities',
            'categoryStats'
        ));
    }
}