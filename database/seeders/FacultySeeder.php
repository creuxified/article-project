<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = [
            'Fakultas Ekonomi dan Bisnis',
            'Fakultas Hukum',
            'Fakultas Ilmu Budaya',
            'Fakultas Ilmu Sosial dan Politik',
            'Fakultas Kedokteran',
            'Fakultas Keolahragaan',
            'Fakultas Keguruan dan Ilmu Pendidikan',
        ];

        foreach ($faculties as $faculty) {
            Faculty::create([
                'name' => $faculty
            ]);
        }
    }
}
