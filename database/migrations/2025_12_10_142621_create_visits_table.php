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
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');

            $table->dateTime('check_in_at');            // Jam Masuk
            $table->dateTime('check_out_at')->nullable(); // Jam Keluar

            $table->integer('duration_minutes')->default(0);
            $table->boolean('got_point')->default(false);

            // KOLOM BARU UNTUK KEPERLUAN
            $table->string('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
