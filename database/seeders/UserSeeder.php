<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mentor;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // 1. AKUN ADMIN
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => 1,
            ]
        );

        // 2. AKUN MENTEE
        User::updateOrCreate(
            ['email' => 'mentee@gmail.com'],
            [
                'name' => 'Ahmad Mentee',
                'password' => Hash::make('password'),
                'role' => 'mentee',
                'is_active' => 1,
            ]
        );

        // 3. AKUN MENTOR (User + Data di Tabel Mentors)
        $mentorUser = User::updateOrCreate(
            ['email' => 'mentor@gmail.com'],
            [
                'name' => 'Budi Mentor',
                'password' => Hash::make('password'),
                'role' => 'mentor',
                'is_active' => 1,
            ]
        );

        // Pastikan mentor ini punya data di tabel mentors supaya gak error pas login
        Mentor::updateOrCreate(
            ['user_id' => $mentorUser->id],
            [
                'bio' => 'Mentor berpengalaman di bidang beasiswa luar negeri.',
                'verification_status' => 'verified',
            ]
        );
    }
}
