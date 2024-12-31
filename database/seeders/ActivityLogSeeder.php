<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ActivityLog::create([
            'actor' => 1, // Assuming the first user created has ID 1
            'faculty' => 1, // Assuming a valid faculty ID
            'program' => 1, // Assuming a valid program ID
            'action' => 'User logged in',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \App\Models\ActivityLog::create([
            'actor' => 2, // Assuming the second user created has ID 2
            'faculty' => 1, // Assuming a valid faculty ID
            'program' => 1, // Assuming a valid program ID
            'action' => 'User requested role',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
