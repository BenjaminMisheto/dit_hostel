<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin')->insert([
            'name' => 'Benjamin Misheto',
            'email' => 'benmisheto@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Ben15831213$'), // Replace 'password' with a secure default password
            'remember_token' => Str::random(10),
            'current_team_id' => null, // or you can set a default team ID if applicable
            'profile_photo_path' => null, // or a default path to a profile photo
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // DB::table('users')->insert([
        //     'name' => 'Benjamin Misheto',
        //     'email' => 'benmisheto@gmail.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('Ben15831213$'), // Replace 'password' with a secure default password
        //     'remember_token' => Str::random(10),
        //     'current_team_id' => null, // or you can set a default team ID if applicable
        //     'profile_photo_path' => null, // or a default path to a profile photo
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
    }
}
