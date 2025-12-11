<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Imports\BooksImport; // Pastikan ini ada jika pakai Import
use Maatwebsite\Excel\Facades\Excel; // Pastikan ini ada jika pakai Import

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        // [BARU] Fix Logic Pencarian
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        $books = $query->latest()->paginate(10);

        // [BARU] Support Live Search
        if ($request->ajax()) {
            return response()->json($books);
        }

        return view('dasbor.buku.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('dasbor.buku.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'location' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $validated['available'] = $validated['stock']; // Set tersedia = stok awal
        Book::create($validated);

        return redirect()->route('dasbor.buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $buku)
    {
        $categories = Category::all();
        return view('dasbor.buku.form', ['book' => $buku, 'categories' => $categories]);
    }

    public function update(Request $request, Book $buku)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'location' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        // Hitung selisih stok jika ada perubahan
        $stok_lama = $buku->stock;
        $stok_baru = $validated['stock'];
        $selisih = $stok_baru - $stok_lama;

        $validated['available'] = $buku->available + $selisih;

        $buku->update($validated);
        return redirect()->route('dasbor.buku.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $buku)
    {
        $buku->delete();
        return redirect()->route('dasbor.buku.index')->with('success', 'Buku berhasil dihapus.');
    }

    // Fungsi Import (Sesuai yang Anda buat sebelumnya)
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        try {
            Excel::import(new BooksImport, $request->file('file'));
            return back()->with('success', 'Data buku berhasil diimport!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
