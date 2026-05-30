<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stoks', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('jumlah_karung');
            $table->integer('berat_kg'); // jumlah_karung * 10
            $table->string('sumber')->nullable(); // nama donatur / OTA
            $table->string('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stoks');
    }
};