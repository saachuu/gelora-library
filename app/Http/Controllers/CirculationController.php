<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CirculationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Loan::with(['book', 'member'])
            ->whereIn('status', ['Dipinjam', 'Terlambat']);

        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->whereHas('member', function($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('member_id_number', 'like', "%{$search}%");
                })->orWhereHas('book', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%");
                });
            });
        }

        $activeLoans = $query->latest()->paginate(10);
        return view('dasbor.sirkulasi.index', compact('activeLoans'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeLoan(Request $request)
    {
        $request->validate([
            'member_id_number' => 'required|exists:members,member_id_number',
            'isbn' => 'required|exists:books,isbn',
        ]);

        $member = Member::where('member_id_number', $request->member_id_number)->first();
        $book = Book::where('isbn', $request->isbn)->first();

        // Cek apakah anggota aktif
        if (!$member->is_active) {
            return back()->with('error', 'Anggota tidak aktif dan tidak dapat meminjam buku.');
        }

        // Cek ketersediaan buku
        if ($book->available <= 0) {
            return back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }

        // Buat record peminjaman
        Loan::create([
            'user_id' => Auth::id(),
            'member_id' => $member->id,
            'book_id' => $book->id,
            'loan_date' => now(),
            'due_date' => now()->addDays(7),
        ]);

        // Kurangi stok tersedia
        $book->decrement('available');

        return redirect()->route('dasbor.sirkulasi.index')->with('success', 'Buku berhasil dipinjamkan.');
    }


    public function storeReturn(Request $request)
    {
        $request->validate([
            'isbn' => 'required|exists:books,isbn',
        ]);

        $book = Book::where('isbn', $request->isbn)->first();

        // Cari peminjaman yang aktif untuk buku ini
        $loan = Loan::where('book_id', $book->id)
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->first();

        if (!$loan) {
            return back()->with('error', 'Tidak ditemukan data peminjaman aktif untuk buku ini.');
        }

        $returnDate = now();
        $dueDate = Carbon::parse($loan->due_date);
        $fine = 0;
        $status = 'Dikembalikan';

        // Cek keterlambatan dan hitung denda
        if ($returnDate->isAfter($dueDate)) {
            $lateDays = $returnDate->diffInDays($dueDate);
            $fine = $lateDays * 1000; // Contoh: denda 1000 per hari
            $status = 'Terlambat'; // Tetap catat sebagai terlambat meskipun sudah dikembalikan
        }

        // Update record peminjaman
        $loan->update([
            'return_date' => $returnDate,
            'status' => 'Dikembalikan', // Status akhir adalah dikembalikan
            'fine' => $fine,
        ]);

        // Tambah stok tersedia
        $book->increment('available');

        return redirect()->route('dasbor.sirkulasi.index')->with('success', "Buku berhasil dikembalikan. Denda: Rp {$fine}");
    }
}