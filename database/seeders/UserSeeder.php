<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // akun admin bawaan
        User::create([
            'username' => 'admin',
            'name' => 'Admin',
            'password' => Hash::make('perpus.skenda')
        ]);
    }
}
