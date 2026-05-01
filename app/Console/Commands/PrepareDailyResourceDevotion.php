<?php

namespace App\Console\Commands;

use App\Services\ResourceHub\ResourceHubService;
use Illuminate\Console\Command;

class PrepareDailyResourceDevotion extends Command
{
    protected $signature = 'resources:prepare-daily-devotion';

    protected $description = 'Ensure a local Resource Hub daily devotion exists for today.';

    public function handle(ResourceHubService $service): int
    {
        $devotion = $service->prepareTodayDevotion();
        $this->info("Daily devotion ready: {$devotion->title}");

        return self::SUCCESS;
    }
}
