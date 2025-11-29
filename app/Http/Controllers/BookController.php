<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('category')->latest()->paginate(10);
        return view('dasbor.buku.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('dasbor.buku.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|digits:4',
            'isbn' => 'required|string|unique:books,isbn',
            'location' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['available'] = $validated['stock'];

        Book::create($validated);

        return redirect()->route('dasbor.buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $buku)
    {
        return view('dasbor.buku.show', compact('buku'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $buku)
    {
        $categories = Category::all();
        return view('dasbor.buku.form', compact('buku', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $buku)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|digits:4',
            'isbn' => 'required|string|unique:books,isbn,' . $buku->id,
            'location' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:Tersedia,Rusak',
        ]);

        // Sesuaikan stok `available` jika stok total diubah
        $stockDifference = $validated['stock'] - $buku->stock;
        $validated['available'] = $buku->available + $stockDifference;
        if ($validated['available'] < 0) {
            $validated['available'] = 0;
        }


        $buku->update($validated);

        return redirect()->route('dasbor.buku.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $buku)
    {
        $buku->delete();
        return redirect()->route('dasbor.buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}