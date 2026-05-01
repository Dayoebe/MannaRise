<?php

namespace Tests\Unit;

use App\Services\ResourceHub\Providers\GutendexProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GutendexProviderTest extends TestCase
{
    public function test_gutendex_provider_normalizes_books(): void
    {
        Cache::flush();

        Http::fake([
            'gutendex.com/books*' => Http::response([
                'results' => [
                    [
                        'id' => 123,
                        'title' => 'A Public Domain Christian Book',
                        'authors' => [['name' => 'Jane Author']],
                        'languages' => ['en'],
                        'subjects' => ['Christian life'],
                        'download_count' => 20,
                        'formats' => [
                            'text/html' => 'https://example.test/book.html',
                            'image/jpeg' => 'https://example.test/cover.jpg',
                        ],
                    ],
                ],
            ]),
        ]);

        $items = app(GutendexProvider::class)->search('christian');

        $this->assertCount(1, $items);
        $this->assertSame('book', $items[0]['type']);
        $this->assertSame('Gutendex', $items[0]['source_name']);
        $this->assertSame('123', $items[0]['external_id']);
        $this->assertSame('Jane Author', $items[0]['author']);
    }
}
