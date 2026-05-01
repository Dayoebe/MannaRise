<?php

namespace App\Console\Commands;

use App\Services\ResourceHub\ResourceHubService;
use Illuminate\Console\Command;
use Throwable;

class SyncResourceBooks extends Command
{
    protected $signature = 'resources:sync-books {query?}';

    protected $description = 'Sync public-domain and open metadata book resources.';

    public function handle(ResourceHubService $service): int
    {
        $keywords = $this->argument('query') ? [$this->argument('query')] : config('resourcehub.book_keywords', []);
        $total = 0;

        foreach ($keywords as $keyword) {
            try {
                $total += $service->import('gutendex', $keyword, ['limit' => 10])->count();
                $total += $service->import('open_library', $keyword, ['limit' => 10])->count();
                $total += $service->import('internet_archive', $keyword, ['limit' => 10, 'mediatype' => 'texts'])->count();
            } catch (Throwable $exception) {
                $this->warn("Book sync failed for {$keyword}: {$exception->getMessage()}");
            }
        }

        $this->info("Book sync complete. {$total} resources refreshed.");

        return self::SUCCESS;
    }
}
