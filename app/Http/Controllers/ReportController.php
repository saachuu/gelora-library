<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with('category');

        // Filter by Category
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Filter by Stock Status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'available') {
                $query->where('available', '>', 0);
            } elseif ($request->status == 'out_of_stock') {
                $query->where('available', '=', 0);
            }
        }

        $books = $query->get()->map(function ($book) {
            $borrowed = $book->stock - $book->available;
            return [
                'title' => $book->title,
                'category' => $book->category->name,
                'stock' => $book->stock,
                'borrowed' => $borrowed,
                'available' => $book->available,
            ];
        });

        $categories = \App\Models\Category::pluck('name', 'id');

        return view('dasbor.laporan.index', compact('books', 'categories'));
    }

    public function export()
    {
        $fileName = 'laporan-ketersediaan-buku-' . date('Y-m-d') . '.csv';
        $books = Book::with('category')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Judul Buku', 'Kategori', 'Total Stok', 'Dipinjam', 'Tersedia');

        $callback = function() use($books, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($books as $book) {
                $borrowed = $book->stock - $book->available;
                $row['Judul Buku']  = $book->title;
                $row['Kategori']    = $book->category->name;
                $row['Total Stok']  = $book->stock;
                $row['Dipinjam']    = $borrowed;
                $row['Tersedia']    = $book->available;

                fputcsv($file, array($row['Judul Buku'], $row['Kategori'], $row['Total Stok'], $row['Dipinjam'], $row['Tersedia']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}