<?php

namespace Database\Seeders;

use App\Models\Foundation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insertOrIgnore([
            [
                'name'       => 'Admin Utama',
                'email'      => 'admin@paskas.my.id',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'phone'      => '08123456789',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // [
            //     'name'       => 'OTA / Donatur',
            //     'email'      => 'ota@ota.paskas.my.id',
            //     'password'   => Hash::make('ota123'),
            //     'role'       => 'ota',
            //     'phone'      => '08234567890',
            //     'is_active'  => true,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            [
                'name'       => 'Pak Kamil',
                'email'      => 'kamil@paskas.my.id',
                'password'   => Hash::make('paskas123'),
                'role'       => 'paskas',
                'phone'      => '08345678901',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Abah Badingsanak',
                'email'      => 'badingsanak@paskas.my.id',
                'password'   => Hash::make('paskas123'),
                'role'       => 'paskas',
                'phone'      => '08456789012',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Miftah',
                'email'      => 'miftah@paskas.my.id',
                'password'   => Hash::make('paskas123'),
                'role'       => 'paskas',
                'phone'      => '08567890123',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Abi Alif',
                'email'      => 'abi@paskas.my.id',
                'password'   => Hash::make('paskas123'),
                'role'       => 'paskas',
                'phone'      => '08678901234',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $foundations = Foundation::all();

    }
}