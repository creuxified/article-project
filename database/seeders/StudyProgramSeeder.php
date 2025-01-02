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
        $FEB_programs = [
            'Akuntansi',
            'Bisnis Digital',
            'Ekonomi Pembangunan',
            'Manajemen',
            'Staff FEB'
        ];

        $FH_programs = [
            'Ilmu Hukum',
            'Staff FH'
        ];

        $FIB_programs = [
            'Bahasa Mandarin dan Kebudayaan Tiongkok',
            'Ilmu Sejarah',
            'Sastra Arab',
            'Sastra Daerah',
            'Sastra Indonesia',
            'Sastra Inggris',
            'Staff FIB'
        ];

        $FISIP_programs = [
            'Administrasi Bisnis',
            'Administrasi Negara',
            'Hubungan Internasional',
            'Ilmu Administrasi Negara',
            'Ilmu Komunikasi',
            'Ilmu Politik',
            'Sosiologi',
            'Staff FISIP'
        ];

        $FK_programs = [
            'Kedokteran',
            'Staff FK'
        ];

        $FKOR_programs = [
            'Pendidikan Jasmani, Kesehatan, dan Rekreasi',
            'Pendidikan Kepelatihan Olahraga',
            'Staff FKOR'
        ];

        $FKIP_programs = [
            'Bimbingan dan Konseling',
            'Pendidikan Akuntansi',
            'Pendidikan Administrasi Perkantoran',
            'Pendidikan Bahasa dan Sastra Indonesia',
            'Pendidikan Bahasa Inggris',
            'Pendidikan Bahasa Jawa',
            'Pendidikan Biologi',
            'Pendidikan Ekonomi',
            'Pendidikan Fisika',
            'Pendidikan Geografi',
            'Pendidikan Guru Pendidikan Anak Usia Dini',
            'Pendidikan Guru Sekolah Dasar',
            'Pendidikan Guru Sekolah Dasar (Kebumen)',
            'Pendidikan Ilmu Pengetahuan Alam',
            'Pendidikan Kimia',
            'Pendidikan Luar Biasa',
            'Pendidikan Matematika',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Pendidikan Sejarah',
            'Pendidikan Seni Rupa',
            'Pendidikan Sosiologi Antropologi',
            'Pendidikan Teknik Bangunan',
            'Pendidikan Teknik Informatika dan Komputer',
            'Pendidikan Teknik Mesin',
            'Teknologi Pendidikan',
            'Staff FKIP'
        ];

        foreach ($FEB_programs as $programs) {
            Study_program::create([
                'name' => $programs,
                'faculty_id' => 1
            ]);
        }

        foreach ($FH_programs as $programs) {
            Study_program::create([
                'name' => $programs,
                'faculty_id' => 2
            ]);
        }

        foreach ($FIB_programs as $programs) {
            Study_program::create([
                'name' => $programs,
                'faculty_id' => 3
            ]);
        }

        foreach ($FISIP_programs as $programs) {
            Study_program::create([
                'name' => $programs,
                'faculty_id' => 4
            ]);
        }

        foreach ($FK_programs as $programs) {
            Study_program::create([
                'name' => $programs,
                'faculty_id' => 5
            ]);
        }

        foreach ($FKOR_programs as $programs) {
            Study_program::create([
                'name' => $programs,
                'faculty_id' => 6
            ]);
        }

        foreach ($FKIP_programs as $programs) {
            Study_program::create([
                'name' => $programs,
                'faculty_id' => 7
            ]);
        }
    }
}
