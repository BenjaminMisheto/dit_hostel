<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeModelAndController extends Command
{
    protected $signature = 'make:model-controller {name}';

    protected $description = 'Create a model and controller with a single command';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');

        // Generate the model
        $this->call('make:model', [
            'name' => $name,
        ]);

        // Generate the controller
        $this->call('make:controller', [
            'name' => "{$name}Controller",
        ]);

        $this->info('Model and controller created successfully.');
    }
}
