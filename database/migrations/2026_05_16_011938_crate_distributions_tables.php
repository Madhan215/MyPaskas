<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('plans');
            $table->foreignId('pondok_id')->constrained('foundations');
            $table->foreignId('seri_id')->constrained('series');
            $table->date('tanggal_distribusi');
            $table->time('jam_distribusi')->nullable();
            $table->integer('jumlah_karung_distribusi');
            $table->integer('jumlah_kg_distribusi');
            $table->text('catatan')->nullable();
            $table->string('foto_bukti')->nullable(); // path foto
            $table->string('foto_watermark')->nullable(); // path foto dengan watermark
            $table->foreignId('user_id')->constrained('users'); // paskas yang input
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributions');
    }
};