<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('foundations', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat');
            $table->integer('jumlah_santri')->default(0);
            $table->integer('jumlah_pengasuh')->default(0);
            $table->string('penanggung_jawab')->nullable(); // Pak Kamil, Abah Badingsanak, dll
            $table->string('no_hp')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foundations');
    }
};