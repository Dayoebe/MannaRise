<?php

namespace Database\Seeders;

use App\Models\DailyDevotion;
use App\Models\ResourceCategory;
use App\Models\ResourceItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResourceHubSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect(config('resourcehub.default_categories'))->mapWithKeys(function (array $category): array {
            $model = ResourceCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'] ?? null,
                    'icon' => $category['icon'] ?? 'library',
                    'type' => $category['type'] ?? null,
                    'is_active' => true,
                ],
            );

            return [$model->type ?: $model->slug => $model];
        });

        $todayDevotion = DailyDevotion::whereDate('devotion_date', today())->first();

        DailyDevotion::updateOrCreate(
            ['devotion_date' => today()->toDateString()],
            [
                'title' => 'Mercy for Today',
                'slug' => $todayDevotion?->slug ?: DailyDevotion::uniqueSlug('Mercy for Today '.today()->toDateString()),
                'bible_reference' => 'Lamentations 3:22-23',
                'bible_text' => 'It is of the Lord\'s mercies that we are not consumed, because his compassions fail not. They are new every morning: great is thy faithfulness.',
                'memory_verse' => 'Great is thy faithfulness.',
                'devotion_text' => 'Today begins with mercy, not pressure. Before you measure what must be done, receive the compassion of God and let faithfulness become your rhythm for the next step.',
                'prayer' => 'Father, steady my heart with Your mercy and teach me to walk faithfully today.',
                'reflection_questions' => ['What mercy can I notice today?', 'What faithful step is God asking me to take?'],
                'action_point' => 'Write down one evidence of God\'s mercy before the day ends.',
                'author' => 'MannaRise',
                'is_published' => true,
            ],
        );

        $items = [
            [
                'category' => 'book',
                'title' => 'The Practice of the Presence of God',
                'type' => 'book',
                'author' => 'Brother Lawrence',
                'excerpt' => 'A classic public-domain Christian book on living with awareness of God.',
                'description' => 'A short devotional classic on prayerfulness, communion with God, and ordinary holiness.',
                'source_name' => 'Project Gutenberg',
                'source_url' => 'https://www.gutenberg.org/ebooks/5657',
                'external_id' => 'gutenberg-5657',
                'license' => 'Public domain',
                'tags' => ['classic', 'prayer', 'public domain'],
            ],
            [
                'category' => 'video',
                'title' => 'BibleProject: What Is the Bible?',
                'type' => 'video',
                'author' => 'BibleProject',
                'excerpt' => 'A concise educational overview of the Bible.',
                'source_name' => 'YouTube',
                'source_url' => 'https://www.youtube.com/watch?v=ak06MSETeo4',
                'embed_url' => 'https://www.youtube.com/embed/ak06MSETeo4',
                'external_id' => 'youtube-ak06MSETeo4',
                'license' => 'Official YouTube embed',
                'tags' => ['bible', 'education', 'video'],
            ],
            [
                'category' => 'audio',
                'title' => 'Public-domain Bible audiobook collection',
                'type' => 'audio',
                'author' => 'LibriVox volunteers',
                'excerpt' => 'Public-domain audiobook resources for listening and study.',
                'description' => 'Use the sync command to import current LibriVox audiobook metadata.',
                'source_name' => 'LibriVox',
                'source_url' => 'https://librivox.org',
                'external_id' => 'librivox-seed',
                'license' => 'Public domain',
                'tags' => ['audio', 'audiobook', 'public domain'],
            ],
            [
                'category' => 'article',
                'title' => 'How to Use the Resource Hub',
                'type' => 'article',
                'excerpt' => 'A quick guide for reading, listening, bookmarking, and tracking growth resources.',
                'content' => 'Choose a resource, read or listen at your pace, bookmark what you want to revisit, and update your progress when you return. The Resource Hub combines local MannaRise content with legal public-domain and official API sources.',
                'source_name' => 'MannaRise',
                'external_id' => 'mannarise-resource-hub-guide',
                'license' => 'MannaRise original',
                'tags' => ['guide'],
            ],
        ];

        foreach ($items as $item) {
            $category = $categories[$item['category']] ?? null;
            $title = $item['title'];

            ResourceItem::updateOrCreate(
                ['source_name' => $item['source_name'], 'external_id' => $item['external_id']],
                [
                    'resource_category_id' => $category?->id,
                    'title' => $title,
                    'slug' => ResourceItem::where('source_name', $item['source_name'])->where('external_id', $item['external_id'])->value('slug') ?: ResourceItem::uniqueSlug($title),
                    'excerpt' => $item['excerpt'] ?? null,
                    'description' => $item['description'] ?? null,
                    'content' => $item['content'] ?? null,
                    'type' => $item['type'],
                    'source_url' => $item['source_url'] ?? null,
                    'author' => $item['author'] ?? null,
                    'media_url' => $item['media_url'] ?? null,
                    'embed_url' => $item['embed_url'] ?? null,
                    'language' => 'en',
                    'license' => $item['license'] ?? null,
                    'tags' => $item['tags'] ?? [],
                    'metadata' => [],
                    'is_featured' => true,
                    'is_published' => true,
                    'published_at' => now(),
                ],
            );
        }
    }
}
