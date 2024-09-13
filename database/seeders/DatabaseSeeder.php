<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(AdminSeeder::class);

        $this->call(ElligableStudentSeeder::class);
        // $this->call(BlockSeeder::class);
        // $this->call(UserSeeder::class);



        // Add more seeder classes here
    }
}
