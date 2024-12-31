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
            'Fakultas Keguruan dan Ilmu Pendidikan',
            'Fakultas Ilmu Sosial dan Politik',
            'Fakultas Hukum',
            'Fakultas Ekonomi dan Bisnis',
            'Fakultas Pertanian',
            'Fakultas Teknik',
            'Fakultas Kedokteran',
            'Fakultas Matematika dan Ilmu Pengetahuan Alam',
            'Fakultas Seni Rupa dan Desain',
            'Fakultas Keolahragaan',
            'Fakultas Ilmu Budaya',
            'Fakultas Teknologi Informasi dan Sains Data'
        ];

        foreach ($faculties as $faculty) {
            Faculty::create([
                'name' => $faculty
            ]);
        }
    }
}
