<?php

namespace Database\Seeders;

use App\Models\Stok;
use Illuminate\Database\Seeder;

class StoksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Stok::insert([
            [
                'tanggal'       => '2025-01-03',
                'jumlah_karung' => 60,
                'berat_kg'      => 600,
                'sumber'        => 'Donatur Anonim',
                'keterangan'    => 'Loading pertama Seri 1',
                'user_id'       => 2, // OTA
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'tanggal'       => '2025-01-10',
                'jumlah_karung' => 50,
                'berat_kg'      => 500,
                'sumber'        => 'Yayasan Al Khair',
                'keterangan'    => 'Loading tambahan Seri 1',
                'user_id'       => 2,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'tanggal'       => '2025-02-01',
                'jumlah_karung' => 99,
                'berat_kg'      => 990,
                'sumber'        => 'Donatur Gabungan',
                'keterangan'    => 'Loading Seri 2 - Total sesuai rencana distribusi',
                'user_id'       => 2,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
