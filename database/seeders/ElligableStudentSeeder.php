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

        // Create the specific student
        ElligableStudent::create([
            'student_name' => 'Benjamin Misheto',
            'registration_number' => 1,
            'payment_status' => $faker->randomElement(['paid', 'pending']),
            'sponsorship' => $faker->randomElement(['government', 'private']),
            'phone' => $faker->phoneNumber,
            'gender' => $faker->randomElement(['Male', 'Female']),
            'nationality' => $faker->country,
            'course' => $faker->randomElement($courses), // Set course randomly from the list
            'email' => $faker->email,
            'image' => 'img/10.jpg'
        ]);


        Publish::create([
            'status' => 0,
        ]);

        // Create other random students
        foreach (range(1, 15) as $index) {
            ElligableStudent::create([
                'student_name' => $faker->name,
                'registration_number' => $faker->unique()->randomNumber(),
                'payment_status' => $faker->randomElement(['pending', 'paid']),
                'sponsorship' => $faker->randomElement(['government', 'private']),
                'phone' => $faker->phoneNumber,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'nationality' => $faker->country,
                'course' => $faker->randomElement($courses), // Set course randomly from the list
                'email' => $faker->email,
                'image' => 'img/' . $index . '.jpg',
            ]);
        }
    }
}
