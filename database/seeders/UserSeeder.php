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
            'email' => 'ptik1@dosen',
            'username' => 'dosenptik1',
            'name' => 'Dosen PTIK',
            'password' => bcrypt('password'),
            'faculty_id' => 7,
            'program_id' => 50,
            'status' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::create([
            'email' => 'ptik2@dosen',
            'username' => 'dosenptik2',
            'name' => 'Dosen PTIK2',
            'password' => bcrypt('password'),
            'faculty_id' => 7,
            'program_id' => 50,
            'status' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::create([
            'email' => 'ptik@prodi',
            'username' => 'adminPTIK',
            'name' => 'Admin Prodi PTIK',
            'password' => bcrypt('password'),
            'faculty_id' => 7,
            'program_id' => 50,
            'status' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::create([
            'email' => 'fkip@admin',
            'username' => 'adminFKIP',
            'name' => 'Admin Fak FKIP',
            'password' => bcrypt('password'),
            'faculty_id' => 7,
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
