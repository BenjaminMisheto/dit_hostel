<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ElligableStudent;
use App\Models\Publish;
use Faker\Factory as Faker;

class ElligableStudentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $courses = ['D1', 'D2', 'D3', 'B1', 'B2', 'B3', 'B4'];

        // Create a publish record
        Publish::create([
            'status' => 0,
        ]);

        // Create 100 random students
        for ($index = 1; $index <= 300; $index++) {
            $imageIndex = ($index % 24) === 0 ? 24 : ($index % 24);

            ElligableStudent::create([
                'student_name' => $faker->name,
                'registration_number' => $faker->unique()->randomNumber(),
                'payment_status' => $faker->randomElement(['pending', 'paid']),
                'sponsorship' => $faker->randomElement(['government', 'private']),
                'phone' => $faker->phoneNumber,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'nationality' => $faker->country,
                'course' => $faker->randomElement(['D1', 'D2', 'D3', 'B1', 'B2', 'B3', 'B4']),
                'email' => $faker->unique()->email, // Ensure the email is unique
                'image' => 'img/' . $imageIndex . '.jpg', // Reset image index after 15
            ]);
        }
    }
}
