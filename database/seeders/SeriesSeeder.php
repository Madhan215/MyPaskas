<?php

namespace Database\Seeders;

use App\Models\Series;
use Illuminate\Database\Seeder;

class SeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Series::insert([
            [
                'nama'            => 'Seri 1 - Januari 2025',
                'bulan'           => 1,
                'tahun'           => 2025,
                'tanggal_mulai'   => '2025-01-01',
                'tanggal_selesai' => '2025-01-31',
                'status'          => 'selesai',
                'keterangan'      => 'Distribusi perdana tahun 2025',
                'user_id'         => 1,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama'            => 'Seri 2 - Februari 2025',
                'bulan'           => 2,
                'tahun'           => 2025,
                'tanggal_mulai'   => '2025-02-01',
                'tanggal_selesai' => '2025-02-28',
                'status'          => 'aktif',
                'keterangan'      => 'Distribusi bulan Februari',
                'user_id'         => 1,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}
