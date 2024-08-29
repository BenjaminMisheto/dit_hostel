<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshAndSeedTable extends Command
{
    protected $signature = 'table:refresh-and-seed';

    protected $description = 'Refresh and seed a specific table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Ask for the migration name
        $migrationName = $this->ask('Enter the name of the migration (without ".php"):');

        // Ask for the seeder name
        $seederName = $this->ask('Enter the name of the seeder class:');

        // Refresh the specific table
        Artisan::call("migrate:refresh", ['--path' => "/database/migrations/{$migrationName}.php"]);

        // Seed the specific table
        Artisan::call("db:seed", ['--class' => $seederName]);

        $this->info("Table '{$migrationName}' refreshed and seeded with seeder '{$seederName}'.");
    }
}
