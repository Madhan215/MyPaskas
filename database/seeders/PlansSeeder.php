<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    public function run(): void
    {
        // Seri 2 (aktif), jadwal distribusi sesuai data tabel gambar
        // 1 santri = 1 kg beras, 1 karung = 10 kg
        // No urut pondok sesuai PondokSeeder
        $jadwals = [
            // PAK KAMIL & TIM (pondok id 1-7)
            ['pondok_id' => 1, 'tanggal' => '2025-02-03', 'karung' => 6, 'kg' => 60, 'petugas' => 'Pak Kamil & Tim'],
            ['pondok_id' => 2, 'tanggal' => '2025-02-03', 'karung' => 6, 'kg' => 60, 'petugas' => 'Pak Kamil & Tim'],
            ['pondok_id' => 3, 'tanggal' => '2025-02-05', 'karung' => 4, 'kg' => 40, 'petugas' => 'Pak Kamil & Tim'],
            ['pondok_id' => 4, 'tanggal' => '2025-02-07', 'karung' => 7, 'kg' => 70, 'petugas' => 'Pak Kamil & Tim'],
            ['pondok_id' => 5, 'tanggal' => '2025-02-08', 'karung' => 7, 'kg' => 70, 'petugas' => 'Pak Kamil & Tim'],
            ['pondok_id' => 6, 'tanggal' => '2025-02-09', 'karung' => 6, 'kg' => 60, 'petugas' => 'Pak Kamil & Tim'],
            ['pondok_id' => 7, 'tanggal' => '2025-02-10', 'karung' => 9, 'kg' => 90, 'petugas' => 'Pak Kamil & Tim'],

            // ABAH BADINGSANAK (pondok id 8-9)
            ['pondok_id' => 8, 'tanggal' => '2025-02-12', 'karung' => 7, 'kg' => 70, 'petugas' => 'Abah Badingsanak'],
            ['pondok_id' => 9, 'tanggal' => '2025-02-12', 'karung' => 4, 'kg' => 40, 'petugas' => 'Abah Badingsanak'],

            // MIFTAH / AMAD (pondok id 10)
            ['pondok_id' => 10, 'tanggal' => '2025-02-14', 'karung' => 4, 'kg' => 40, 'petugas' => 'Miftah / Amad'],

            // ABI ALIF & TIM (pondok id 11-14)
            ['pondok_id' => 11, 'tanggal' => '2025-02-17', 'karung' => 10, 'kg' => 100, 'petugas' => 'Abi Alif & Tim'],
            ['pondok_id' => 12, 'tanggal' => '2025-02-18', 'karung' => 10, 'kg' => 100, 'petugas' => 'Abi Alif & Tim'],
            ['pondok_id' => 13, 'tanggal' => '2025-02-19', 'karung' => 4, 'kg' => 40, 'petugas' => 'Abi Alif & Tim'],
            ['pondok_id' => 14, 'tanggal' => '2025-02-20', 'karung' => 10, 'kg' => 100, 'petugas' => 'Abi Alif & Tim'],

            // FISABILILLAH (pondok id 15)
            ['pondok_id' => 15, 'tanggal' => '2025-02-25', 'karung' => 5, 'kg' => 50, 'petugas' => 'Admin'],
        ];

        foreach ($jadwals as $j) {
            Plan::insert([
                'seri_id'       => 2,
                'pondok_id'     => $j['pondok_id'],
                'tanggal'       => $j['tanggal'],
                'jumlah_karung' => $j['karung'],
                'jumlah_kg'     => $j['kg'],
                'petugas'       => $j['petugas'],
                'status'        => 'belum',
                'catatan'       => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}