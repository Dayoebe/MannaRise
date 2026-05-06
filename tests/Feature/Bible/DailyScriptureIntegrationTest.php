<?php

namespace Tests\Feature\Bible;

use App\Livewire\Daily\Index as DailyIndex;
use App\Models\DailyScripture;
use App\Models\MemoryVerseProgress;
use App\Models\User;
use App\Services\Bible\BibleApiComProvider;
use App\Services\Bible\BibleProviderInterface;
use App\Services\Bible\BibleVerseData;
use App\Services\Bible\BibleVerseService;
use App\Services\Bible\OurMannaProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class DailyScriptureIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_bible_api_com_provider_parses_passage_response(): void
    {
        Http::fake([
            'https://bible-api.com/*' => Http::response([
                'reference' => 'John 3:16',
                'text' => 'For God so loved the world.',
                'translation_id' => 'kjv',
                'verses' => [
                    ['book_name' => 'John', 'chapter' => 3, 'verse' => 16],
                ],
            ]),
        ]);

        $verse = app(BibleApiComProvider::class)->getPassage('John 3:16', 'kjv');

        $this->assertSame('bible_api_com', $verse?->provider);
        $this->assertSame('John 3:16', $verse?->reference);
        $this->assertSame('For God so loved the world.', $verse?->text);
        $this->assertSame('kjv', $verse?->translation);
        $this->assertSame('John', $verse?->book);
        $this->assertSame(3, $verse?->chapter);
        $this->assertSame('16', $verse?->verse);
    }

    public function test_our_manna_provider_parses_daily_response(): void
    {
        Http::fake([
            'https://beta.ourmanna.com/api/v1/get*' => Http::response([
                'verse' => [
                    'details' => [
                        'text' => 'The Lord is my shepherd.',
                        'reference' => 'Psalm 23:1',
                        'version' => 'KJV',
                        'bookname' => 'Psalms',
                        'chapter' => '23',
                        'verse' => '1',
                    ],
                ],
            ]),
        ]);

        $verse = app(OurMannaProvider::class)->getDailyVerse();

        $this->assertSame('our_manna', $verse?->provider);
        $this->assertSame('Psalm 23:1', $verse?->reference);
        $this->assertSame('The Lord is my shepherd.', $verse?->text);
        $this->assertSame('KJV', $verse?->translation);
    }

    public function test_bible_verse_service_falls_back_when_provider_fails(): void
    {
        $service = new BibleVerseService([
            'first' => new class implements BibleProviderInterface
            {
                public function name(): string
                {
                    return 'first';
                }

                public function isConfigured(): bool
                {
                    return true;
                }

                public function getDailyVerse(?string $translation = null): ?BibleVerseData
                {
                    throw new \RuntimeException('Down');
                }

                public function getRandomVerse(?string $translation = null): ?BibleVerseData
                {
                    return null;
                }

                public function getPassage(string $reference, ?string $translation = null): ?BibleVerseData
                {
                    return null;
                }
            },
            'second' => new class implements BibleProviderInterface
            {
                public function name(): string
                {
                    return 'second';
                }

                public function isConfigured(): bool
                {
                    return true;
                }

                public function getDailyVerse(?string $translation = null): ?BibleVerseData
                {
                    return new BibleVerseData('second', 'Romans 8:28', 'All things work together for good.', 'kjv');
                }

                public function getRandomVerse(?string $translation = null): ?BibleVerseData
                {
                    return null;
                }

                public function getPassage(string $reference, ?string $translation = null): ?BibleVerseData
                {
                    return null;
                }
            },
        ]);

        $verse = $service->getDailyVerse('first', today(), true);

        $this->assertSame('second', $verse?->provider);
        $this->assertSame('Romans 8:28', $verse?->reference);
    }

    public function test_sync_daily_scripture_command_creates_today_row(): void
    {
        Http::fake([
            'https://bible-api.com/data/web/random' => Http::response([
                'random_verse' => [
                    'book' => 'John',
                    'chapter' => 15,
                    'verse' => 5,
                    'text' => 'I am the vine.',
                ],
                'translation' => ['identifier' => 'web'],
            ]),
        ]);

        $this->artisan('mannarise:sync-daily-scripture --force')
            ->assertSuccessful();

        $this->assertDatabaseHas('daily_scriptures', [
            'verse_date' => today()->toDateString(),
            'reference' => 'John 15:5',
            'provider' => 'bible_api_com',
        ]);
    }

    public function test_daily_page_displays_locally_stored_daily_scripture(): void
    {
        DailyScripture::create([
            'provider' => 'bible_api_com',
            'reference' => 'John 3:16',
            'book' => 'John',
            'chapter' => 3,
            'verse' => '16',
            'translation' => 'kjv',
            'text' => 'For God so loved the world.',
            'verse_date' => today(),
            'is_active' => true,
            'fetched_at' => now(),
        ]);

        $this->get('/daily')
            ->assertOk()
            ->assertSee('Today&apos;s scripture', false)
            ->assertSee('John 3:16')
            ->assertSee('For God so loved the world.');
    }

    public function test_authenticated_user_can_save_daily_scripture_to_memory_verses(): void
    {
        $user = User::factory()->create();

        DailyScripture::create([
            'provider' => 'bible_api_com',
            'reference' => 'John 3:16',
            'book' => 'John',
            'chapter' => 3,
            'verse' => '16',
            'translation' => 'kjv',
            'text' => 'For God so loved the world.',
            'verse_date' => today(),
            'is_active' => true,
            'fetched_at' => now(),
        ]);

        Livewire::actingAs($user)
            ->test(DailyIndex::class)
            ->call('saveDailyScriptureToMemory')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('memory_verse_progress', [
            'user_id' => $user->id,
            'reference' => 'John 3:16',
            'verse_text' => 'For God so loved the world.',
        ]);

        Livewire::actingAs($user)
            ->test(DailyIndex::class)
            ->call('saveDailyScriptureToMemory');

        $this->assertSame(1, MemoryVerseProgress::where('user_id', $user->id)->where('reference', 'John 3:16')->count());
    }
}
