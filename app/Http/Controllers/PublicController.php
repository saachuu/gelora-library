<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with('category')->where('status', 'Tersedia');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhereHas('category', function($catQ) use ($search) {
                      $catQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $books = $query->latest()->paginate(12);

        return view('welcome', compact('books'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        // Pastikan hanya buku yang tersedia yang bisa dilihat detailnya oleh publik
        if ($book->status !== 'Tersedia') {
            abort(404);
        }
        return view('buku.show', compact('book'));
    }
}