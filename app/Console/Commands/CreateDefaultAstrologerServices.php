<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Astrologer;
use App\Models\Service;
use App\Models\AstrologerService;

class CreateDefaultAstrologerServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'astrologer:create-default-services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default service entries for existing astrologers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Creating default service entries for existing astrologers...');

        $astrologers = Astrologer::all();
        $services = Service::where('is_active', true)->get();

        $bar = $this->output->createProgressBar($astrologers->count());
        $bar->start();

        foreach ($astrologers as $astrologer) {
            foreach ($services as $service) {
                AstrologerService::updateOrCreate(
                    [
                        'astrologer_id' => $astrologer->id,
                        'service_id' => $service->id,
                    ],
                    [
                        'is_enabled' => true
                    ]
                );
            }
            $bar->advance();
        }

        $bar->finish();
        $this->line('');
        $this->info('Default service entries created successfully!');

        return 0;
    }
}
