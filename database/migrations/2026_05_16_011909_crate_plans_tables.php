<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seri_id')->constrained('series');
            $table->foreignId('pondok_id')->constrained('foundations');
            $table->date('tanggal');
            $table->integer('jumlah_karung');
            $table->integer('jumlah_kg');
            $table->string('petugas')->nullable(); // nama paskas yang bertugas
            $table->enum('status', ['belum', 'selesai', 'ditunda'])->default('belum');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};