<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CirculationController extends Controller
{
    public function index()
    {
        return view('dasbor.sirkulasi.index');
    }

    /**
     * API: Cari Anggota (Live Search)
     */
    public function searchMembers(Request $request)
    {
        $search = $request->get('q');

        $members = Member::where('is_active', true)
            ->where(function($query) use ($search) {
                $query->where('member_id_number', 'like', "%{$search}%")
                      ->orWhere('full_name', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        $results = $members->map(function($member) {
            // Cek pinjaman aktif
            $activeLoan = Loan::with('book')
                ->where('member_id', $member->id)
                ->whereIn('status', ['Dipinjam', 'Terlambat'])
                ->first();

            return [
                'id' => $member->id,
                'text' => $member->member_id_number . ' - ' . $member->full_name,
                'has_loan' => $activeLoan ? true : false,
                'loan_details' => $activeLoan ? [
                    'book_title' => $activeLoan->book->title,
                    'due_date' => Carbon::parse($activeLoan->due_date)->format('d M Y'),
                    'is_overdue' => Carbon::now()->gt($activeLoan->due_date)
                ] : null
            ];
        });

        return response()->json($results);
    }

    /**
     * API: Cari Buku (Live Search)
     */
    public function searchBooks(Request $request)
    {
        $search = $request->get('q');

        $books = Book::where('status', 'Tersedia')
            ->where('available', '>', 0)
            ->where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        $results = $books->map(function($book) {
            return [
                'id' => $book->id,
                'text' => $book->title . ' (Stok: ' . $book->available . ')',
                'cover' => $book->cover_image_url
            ];
        });

        return response()->json($results);
    }

    /**
     * Proses Simpan Peminjaman
     */
    public function storeLoan(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_id' => 'required|exists:books,id',
        ]);

        // 1. Cek apakah siswa punya tanggungan?
        $hasActiveLoan = Loan::where('member_id', $request->member_id)
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->exists();

        if ($hasActiveLoan) {
            return back()->with('error', 'Siswa ini masih meminjam buku. Harap kembalikan terlebih dahulu.');
        }

        // 2. Cek stok buku
        $book = Book::find($request->book_id);
        if ($book->available < 1) {
            return back()->with('error', 'Stok buku ini sedang habis.');
        }

        // 3. Simpan Transaksi
        try {
            \DB::transaction(function() use ($request, $book) {
                $book->decrement('available');

                Loan::create([
                    'user_id' => auth()->id(),
                    'member_id' => $request->member_id,
                    'book_id' => $request->book_id,
                    'loan_date' => now(),
                    'due_date' => now()->addWeeks(2), // FIX 2 Minggu
                    'status' => 'Dipinjam',
                    'fine' => 0
                ]);
            });

            return back()->with('success', 'Peminjaman berhasil. Jatuh tempo 2 minggu.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Proses Simpan Pengembalian
     */
    public function storeReturn(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        // 1. Cari data pinjaman aktif
        $loan = Loan::where('member_id', $request->member_id)
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->first();

        if (!$loan) {
            return back()->with('error', 'Siswa ini tidak memiliki pinjaman aktif.');
        }

        // 2. Hitung Denda
        $returnDate = now();
        $fine = 0;

        if ($returnDate->gt($loan->due_date)) {
            $daysOverdue = $returnDate->diffInDays($loan->due_date);
            $fine = $daysOverdue * 1000; // Rp 1.000/hari
        }

        // 3. Simpan Transaksi
        try {
            \DB::transaction(function() use ($loan, $returnDate, $fine) {
                $loan->update([
                    'return_date' => $returnDate,
                    'status' => $fine > 0 ? 'Terlambat' : 'Dikembalikan',
                    'fine' => $fine
                ]);

                $loan->book->increment('available');
            });

            $msg = 'Buku dikembalikan.';
            if ($fine > 0) $msg .= ' Denda: Rp ' . number_format($fine, 0, ',', '.');

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pengembalian.');
        }
    }
}
