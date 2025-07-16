<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class IdeHelperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ide-helper:generate {--filename=} {--write}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'IDE Helper command placeholder - Laravel IDE Helper not installed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Laravel IDE Helper is not installed in this project.');
        $this->info('To install it, run: composer require --dev barryvdh/laravel-ide-helper');
        $this->info('Note: This may cause dependency conflicts with Laravel 7.');

        return 0;
    }
}
