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
            'email' => 'dummy1@gmail.com',
            'username' => 'dummy1',
            'name' => 'dummy fkip dosen',
            'password' => bcrypt('password'),
            'faculty_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::create([
            'email' => 'dummy2@gmail.com',
            'username' => 'dummy2',
            'name' => 'dummy fisip dosen',
            'password' => bcrypt('password'),
            'faculty_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::create([
            'email' => 'dummy3@gmail.com',
            'username' => 'dummy3',
            'name' => 'dummy fkip admin prodi',
            'password' => bcrypt('password'),
            'faculty_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::create([
            'email' => 'prodifkip@admin',
            'username' => 'admprodifkip',
            'name' => 'Admin Prodi FKIP',
            'password' => bcrypt('password'),
            'faculty_id' => 1,
            'program_id' => 1,
            'role_id' => 3,
            'status' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::create([
            'email' => 'prodifisip@admin',
            'username' => 'admprodifisip',
            'name' => 'Admin Prodi FISIP',
            'password' => bcrypt('password'),
            'faculty_id' => 2,
            'program_id' => 15,
            'role_id' => 3,
            'status' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::create([
            'email' => 'fkip@admin',
            'username' => 'admfakfkip',
            'name' => 'Admin Fakultas FKIP',
            'password' => bcrypt('password'),
            'faculty_id' => 1,
            'program_id' => 1,
            'role_id' => 4,
            'status' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::create([
            'email' => 'uns@admin',
            'username' => 'admUniv',
            'name' => 'Admin univ',
            'password' => bcrypt('password'),
            'role_id' => 5,
            'status' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
