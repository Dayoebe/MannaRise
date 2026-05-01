<?php

namespace Tests\Feature;

use App\Models\ResourceCategory;
use App\Models\ResourceItem;
use App\Services\ResourceHub\ResourceHubService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceHubServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_hub_service_stores_normalized_items_without_duplicates(): void
    {
        ResourceCategory::create([
            'name' => 'Books',
            'slug' => 'books',
            'type' => 'book',
            'is_active' => true,
        ]);

        $service = app(ResourceHubService::class);

        $payload = [
            'title' => 'Grace Book',
            'type' => 'book',
            'source_name' => 'Test Provider',
            'external_id' => 'abc-1',
            'excerpt' => 'A useful public resource.',
            'language' => 'en',
        ];

        $service->storeNormalizedItem($payload);
        $service->storeNormalizedItem([...$payload, 'excerpt' => 'Updated excerpt.']);

        $this->assertSame(1, ResourceItem::count());
        $this->assertDatabaseHas('resource_items', [
            'title' => 'Grace Book',
            'source_name' => 'Test Provider',
            'external_id' => 'abc-1',
            'excerpt' => 'Updated excerpt.',
        ]);
    }
}
