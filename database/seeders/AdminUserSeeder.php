<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@contoh.com'], // Mencari user berdasarkan email ini
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('password'), // Hash otomatis untuk keamanan
                'is_admin' => 1, // Memberikan hak akses admin
                'email_verified_at' => now(), // Menandai email sudah terverifikasi
            ]
        );
    }
}