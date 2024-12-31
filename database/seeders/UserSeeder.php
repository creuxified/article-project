<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'ardenio88@gmail.com',
            'username' => 'ronarden',
            'name' => 'Muhammad Arden Prabaswara',
            'password' => bcrypt('password'),
            'faculty_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::create([
            'email' => 'admin@admin',
            'username' => 'AdminProdi',
            'name' => 'Admin Prod1 x',
            'password' => bcrypt('password'),
            'faculty_id' => 1,
            'program_id' => 1,
            'role_id' => 2,
            'status' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
