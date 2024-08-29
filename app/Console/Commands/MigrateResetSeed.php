<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateResetSeed extends Command
{
    protected $signature = 'migrate:reset-seed';

    protected $description = 'Reset migrations, optimize, and seed the database';

    public function handle()
    {
        // Reset migrations
        $this->call('migrate:reset');

        // Seed the database
        $this->call('migrate', ['--seed' => true]);

         // Optimize the application
         $this->call('optimize');
    }
}
