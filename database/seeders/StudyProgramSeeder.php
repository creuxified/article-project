<?php

namespace Database\Seeders;

use App\Models\study_program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $FKIP_programs = [
            'Pendidikan Bahasa dan Sastra Indonesia',
            'Pendidikan Bahasa Inggris',
            'Pendidikan Matematika',
            'Pendidikan Fisika',
            'Pendidikan Kimia',
            'Pendidikan Biologi',
            'Pendidikan Sejarah',
            'Pendidikan Geografi',
            'Pendidikan Ekonomi',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Pendidikan Guru Sekolah Dasar',
            'Pendidikan Guru Pendidikan Anak Usia Dini',
            'Pendidikan Luar Biasa',
            'Bimbingan dan Konseling',
            'Staff FKIP'
        ];

        $FISIP_programs = [
            'Ilmu Administrasi Negara',
            'Ilmu Komunikasi',
            'Sosiologi',
            'Hubungan Internasional',
            'Ilmu Politik',
            'Administrasi Bisnis',
            'Staff FISIP'
        ];

        foreach ($FKIP_programs as $programs){
            Study_program::create([
                'name' => $programs,
                'faculty_id' => 1
            ]);
        }

        foreach ($FISIP_programs as $programs){
            Study_program::create([
                'name' => $programs,
                'faculty_id' => 2
            ]);
        }
    }
}
