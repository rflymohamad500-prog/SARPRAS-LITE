<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@sekolah.id',
            'password' => Hash::make('password'), // password: password
            'role' => 'admin',
        ]);

        // Buat Petugas
        User::create([
            'name' => 'Petugas Sarpras',
            'email' => 'petugas@sekolah.id',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);
    }
}
