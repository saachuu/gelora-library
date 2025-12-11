<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'member_id',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'fine',
    ];

    // Relasi ke User (Admin/Pustakawan)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Member (Siswa) -- PENTING UNTUK LAPORAN
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    // Relasi ke Buku -- PENTING UNTUK LAPORAN
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
