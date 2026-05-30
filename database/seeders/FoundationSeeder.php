<?php

namespace Database\Seeders;

use App\Models\Foundation;
use Illuminate\Database\Seeder;

class FoundationSeeder extends Seeder
{
    public function run(): void
    {
        // Data dari tabel distribusi beras
        $pondoks = [
            // Kelompok PAK KAMIL & TIM
            ['nama' => 'Yayasan Baiturahman', 'alamat' => 'A Yani', 'jumlah_santri' => 25, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Pak Kamil & Tim'],
            ['nama' => 'RTQ Ummul Qura', 'alamat' => 'Bumi Mas', 'jumlah_santri' => 15, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Pak Kamil & Tim'],
            ['nama' => 'LKSA Bunda Qurbanur', 'alamat' => 'Uvaya', 'jumlah_santri' => 8, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Pak Kamil & Tim'],
            ['nama' => 'Ponpes Al Aminiah', 'alamat' => 'Basirih', 'jumlah_santri' => 20, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Pak Kamil & Tim'],
            ['nama' => 'Al Haramain', 'alamat' => 'Pekapuran', 'jumlah_santri' => 31, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Pak Kamil & Tim'],
            ['nama' => 'RTQ Dar Mashyur', 'alamat' => 'Teluk Tiram', 'jumlah_santri' => 12, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Pak Kamil & Tim'],
            ['nama' => 'Ponpes Khuluqul Hasan', 'alamat' => 'Kebun Bunga', 'jumlah_santri' => 130, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Pak Kamil & Tim'],

            // Kelompok ABAH BADINGSANAK
            ['nama' => 'Arrahmatul Abadiyah', 'alamat' => 'JL Pangeran', 'jumlah_santri' => 25, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Abah Badingsanak'],
            ['nama' => 'LKSA Ashabul Kahfi', 'alamat' => 'Sultan Adam', 'jumlah_santri' => 7, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Abah Badingsanak'],

            // Kelompok MIFTAH / AMAD
            ['nama' => 'PTQ Khairul Ilmi', 'alamat' => 'Sungai Andai', 'jumlah_santri' => 12, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Miftah / Amad'],

            // Kelompok ABI ALIF & TIM
            ['nama' => 'Ponpes Al Ihsan', 'alamat' => 'Kamp Melayu', 'jumlah_santri' => 200, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Abi Alif & Tim'],
            ['nama' => 'Pondok Darul Ihsan Batola', 'alamat' => 'Anjir', 'jumlah_santri' => 22, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Abi Alif & Tim'],
            ['nama' => 'Yayasan Mandiri Anak Banua', 'alamat' => 'Cempaka', 'jumlah_santri' => 55, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Abi Alif & Tim'],
            ['nama' => 'Pondok Darul Ma\'arif', 'alamat' => 'Berangas', 'jumlah_santri' => 10, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Abi Alif & Tim'],

            // Lainnya
            ['nama' => 'Fisabilillah', 'alamat' => '-', 'jumlah_santri' => 5, 'jumlah_pengasuh' => 0, 'penanggung_jawab' => 'Admin'],
        ];

        foreach ($pondoks as $pondok) {
            Foundation::insert(array_merge($pondok, [
                'no_hp'      => null,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}