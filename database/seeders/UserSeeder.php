<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Bendesa Adat
        User::updateOrCreate(
            ['email' => 'bendesa@spda.com'],
            [
                'name' => 'Bendesa Adat Sample',
                'password' => Hash::make('password123'),
                'id_level' => 1,
                'no_wa' => '081234567890',
                'aktif' => '1',
            ]
        );

        // 2. Kelian Adat
        User::updateOrCreate(
            ['email' => 'kelian@spda.com'],
            [
                'name' => 'Kelian Adat Sample',
                'password' => Hash::make('password123'),
                'id_level' => 2,
                'no_wa' => '081234567891',
                'aktif' => '1',
            ]
        );

        // 3. Unit Usaha
        User::updateOrCreate(
            ['email' => 'usaha@spda.com'],
            [
                'name' => 'Unit Usaha Sample',
                'password' => Hash::make('password123'),
                'id_level' => 3,
                'no_wa' => '081234567892',
                'aktif' => '1',
            ]
        );
    }
}
