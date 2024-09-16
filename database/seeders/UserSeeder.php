<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Bed;
use App\Models\Semester;
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

        // Populate the user table with users who have already applied for beds
        foreach ($beds as $bed) {
            $room = $bed->room;
            $floor = $room->floor;
            $block = $floor->block;

            // Create the user for the open semester
            $user = User::create([
                'name' => $faker->name,
                'semester_id' => $openSemester->id, // Assign only to the open semester

                'registration_number' => $faker->unique()->numerify('#########'), // Generates a 9-digit number
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
                'sponsorship' => $faker->randomElement(['government', 'private']),
                'phone' => $faker->phoneNumber,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'nationality' => $faker->country,
                'course' => $faker->randomElement(['D1', 'D2', 'D3', 'B1', 'B2', 'B3', 'B4']),
                'email' => $faker->unique()->safeEmail, // Ensure the email is unique
                'password' => bcrypt('password'), // Or use a hash that you prefer
                'profile_photo_path' => 'img/' . (($bed->id % 15) + 1) . '.jpg', // Cycle image index from 1 to 15
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
