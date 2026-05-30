<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // contoh: "Seri 1 - Januari 2025"
            $table->integer('bulan'); // 1-12
            $table->integer('tahun');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users'); // dibuat oleh
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};