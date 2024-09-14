<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;
use App\Models\Block;
use App\Models\User;
use App\Models\Bed;
use Faker\Factory as Faker;

class SemesterSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Define semester names and their start/end dates
        $semesters = [
            ['name' => 'Semester 1 2025/2026', 'start_date' => '2025-09-01', 'end_date' => '2026-01-31'],
            ['name' => 'Semester 2 2025/2026', 'start_date' => '2026-02-01', 'end_date' => '2026-06-30'],
            ['name' => 'Semester 1 2026/2027', 'start_date' => '2026-09-01', 'end_date' => '2027-01-31'],
            ['name' => 'Semester 2 2026/2027', 'start_date' => '2027-02-01', 'end_date' => '2027-06-30'],
            ['name' => 'Semester 1 2027/2028', 'start_date' => '2027-09-01', 'end_date' => '2028-01-31'],
            ['name' => 'Semester 2 2027/2028', 'start_date' => '2028-02-01', 'end_date' => '2028-06-30'],
            ['name' => 'Semester 1 2028/2029', 'start_date' => '2028-09-01', 'end_date' => '2029-01-31'],
            ['name' => 'Semester 2 2028/2029', 'start_date' => '2029-02-01', 'end_date' => '2029-06-30'],
            ['name' => 'Semester 1 2029/2030', 'start_date' => '2029-09-01', 'end_date' => '2030-01-31'],
            ['name' => 'Semester 2 2029/2030', 'start_date' => '2030-02-01', 'end_date' => '2030-06-30'],
        ];

// Create the semesters and mark only the last one as open
foreach ($semesters as $index => $semesterData) {
    Semester::create([
        'name' => $semesterData['name'],
        'start_date' => $semesterData['start_date'],
        'end_date' => $semesterData['end_date'],
        'is_closed' => $index === count($semesters) - 1 ? false : true, // All closed except the last semester
    ]);
}

    }
}
