<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Bed;
use App\Models\Semester;
use App\Models\ElligableStudent;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Fetch the most recent semester where is_closed = 0
        $openSemester = Semester::where('is_closed', 0)->orderBy('start_date', 'desc')->first();

        if (!$openSemester) {
            throw new \Exception('No open semester found. Please run the SemesterSeeder and ensure there is an open semester.');
        }

        // Fetch all beds and their related data (room, floor, block)
        $beds = Bed::with(['room.floor.block'])
            ->inRandomOrder()
            ->limit(200)
            ->get();

        // Fetch elligible students to map them to users
        $elligibleStudents = ElligableStudent::inRandomOrder()
            ->limit($beds->count())
            ->get();

        // Ensure we have enough elligible students to map to beds
        if ($elligibleStudents->count() < $beds->count()) {
            throw new \Exception('Not enough elligible students to map to the available beds.');
        }

        // Populate the user table using eligible student data
        foreach ($beds as $index => $bed) {
            $room = $bed->room;
            $floor = $room->floor;
            $block = $floor->block;

            // Fetch corresponding eligible student
            $elligibleStudent = $elligibleStudents[$index];

            // Create the user for the open semester
            $user = User::create([
                'name' => $elligibleStudent->student_name, // Use the name from eligible student
                'registration_number' => $elligibleStudent->registration_number, // Use the registration number from eligible student
                'semester_id' => $openSemester->id, // Assign only to the open semester

                'confirmation' => 1,
                'application' => 1,
                'status' => 'approved',
                'payment_status' => $faker->optional()->randomElement([
                    $block->price ?? $faker->numerify('#########'), // Use block price if available, otherwise generate a 9-digit number
                    null,
                ]),
                'Control_Number' => $faker->unique()->numerify('#########'), // Generates a 9-digit number
                'block_id' => $block->id,
                'room_id' => $room->id,
                'floor_id' => $floor->id,
                'bed_id' => $bed->id,
                'sponsorship' => $elligibleStudent->sponsorship, // Use sponsorship from eligible student
                'phone' => $elligibleStudent->phone, // Use phone from eligible student
                'gender' => $elligibleStudent->gender, // Use gender from eligible student
                'nationality' => $elligibleStudent->nationality, // Use nationality from eligible student
                'course' => $elligibleStudent->course, // Use course from eligible student
                'email' => $elligibleStudent->email, // Use email from eligible student
                'password' => bcrypt('password'), // Or use a hash that you prefer
                'profile_photo_path' => $elligibleStudent->image,
                'expiration_date' => $faker->boolean ? $faker->dateTimeBetween('now', 'now') : $faker->dateTimeBetween('now', '+1 month'),
                'created_at' => now(),
                'updated_at' => now(),
                'counter' => $faker->numberBetween(0, 10),
            ]);

            // Update the bed with the user_id of the created user
            $bed->update(['user_id' => $user->id]);
        }
    }
}
