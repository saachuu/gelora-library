<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation; // Tambah ini
use Illuminate\Validation\Rule;

class BooksImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Cari Kategori atau Buat Baru
        // Gunakan trim() agar spasi tidak dianggap beda ("Umum " vs "Umum")
        $categoryName = isset($row['kategori']) ? trim($row['kategori']) : 'Umum';
        $category = Category::firstOrCreate(['name' => $categoryName]);

        return new Book([
            'title'       => $row['judul'],
            'author'      => $row['penulis'],
            'isbn'        => $row['isbn'] ?? null,
            'stock'       => $row['stok'] ?? 0,
            'available'   => $row['stok'] ?? 0,
            'location'    => $row['rak'] ?? 'Rak Umum',
            'category_id' => $category->id,
            'description' => 'Diimport dari Excel',
        ]);
    }

    // Aturan Validasi per Baris
    public function rules(): array
    {
        return [
            'judul'   => 'required|string',
            'penulis' => 'required|string',
            'stok'    => 'required|numeric|min:0',
        ];
    }
}
