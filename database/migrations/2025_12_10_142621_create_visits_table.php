<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            // Relasi ke member. Jika member dihapus, history kunjungan ikut terhapus.
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');

            $table->dateTime('check_in_at');
            $table->dateTime('check_out_at')->nullable();

            // Kolom hasil kalkulasi
            $table->integer('duration_minutes')->default(0);
            $table->boolean('got_point')->default(false); // True jika > 10 menit

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
