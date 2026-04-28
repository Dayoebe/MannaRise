<?php

namespace App\Support;

use App\Models\BibleBook;
use App\Models\Devotional;
use App\Models\DevotionalCategory;

class PersonalizedDailyPath
{
    public static function seasons(): array
    {
        return [
            'peace' => [
                'label' => 'Peace and anxiety',
                'terms' => ['peace', 'faith', 'prayer'],
                'reference' => 'Philippians 4:6-7',
                'book' => 'philippians',
                'chapter' => 4,
                'affirmation' => 'God guards my heart and mind with His peace.',
                'prayer' => 'Lord, settle my heart and teach me to bring every concern to You.',
                'journal_prompt' => 'What concern do I need to release to God today?',
                'action' => 'Pause for three quiet minutes before the next major task.',
            ],
            'faith' => [
                'label' => 'Faith and courage',
                'terms' => ['faith', 'purpose', 'spiritual growth'],
                'reference' => 'Joshua 1:9',
                'book' => 'joshua',
                'chapter' => 1,
                'affirmation' => 'The Lord is with me, so I can move with courage.',
                'prayer' => 'Father, strengthen my faith where I have been hesitant.',
                'journal_prompt' => 'Where is obedience asking me for courage?',
                'action' => 'Take one clear step toward the thing God has placed before you.',
            ],
            'healing' => [
                'label' => 'Healing and restoration',
                'terms' => ['healing', 'hope', 'prayer'],
                'reference' => 'Psalm 147:3',
                'book' => 'psalms',
                'chapter' => 147,
                'affirmation' => 'God is near to my wounds and faithful to restore me.',
                'prayer' => 'Lord, heal what is wounded and renew what has grown tired.',
                'journal_prompt' => 'What part of my story needs God\'s healing touch?',
                'action' => 'Pray honestly over one painful place without rushing past it.',
            ],
            'purpose' => [
                'label' => 'Purpose and calling',
                'terms' => ['purpose', 'business', 'spiritual growth'],
                'reference' => 'Ephesians 2:10',
                'book' => 'ephesians',
                'chapter' => 2,
                'affirmation' => 'I am God\'s workmanship, created for good works.',
                'prayer' => 'Lord, align my work, gifts, and decisions with Your purpose.',
                'journal_prompt' => 'Which gift or responsibility needs faithful stewardship today?',
                'action' => 'Write down one assignment you can serve well today.',
            ],
            'family' => [
                'label' => 'Family and relationships',
                'terms' => ['family', 'love', 'faith'],
                'reference' => 'Colossians 3:13-14',
                'book' => 'colossians',
                'chapter' => 3,
                'affirmation' => 'I can walk in patience, forgiveness, and love.',
                'prayer' => 'Lord, make my words gentle and my love practical.',
                'journal_prompt' => 'Who needs patience, forgiveness, or encouragement from me?',
                'action' => 'Send one sincere encouragement to someone close to you.',
            ],
        ];
    }

    public static function forSeason(?string $season): array
    {
        $season = array_key_exists($season ?? '', self::seasons()) ? $season : 'peace';
        $definition = self::seasons()[$season];

        return [
            'key' => $season,
            'definition' => $definition,
            'devotional' => self::recommendedDevotional($definition['terms']),
            'category' => self::recommendedCategory($definition['terms']),
            'bible_book' => BibleBook::where('slug', $definition['book'])->first(),
        ];
    }

    private static function recommendedDevotional(array $terms): ?Devotional
    {
        return Devotional::query()
            ->with('category')
            ->published()
            ->where(function ($query) use ($terms): void {
                foreach ($terms as $term) {
                    $query->orWhere('title', 'like', "%{$term}%")
                        ->orWhere('content', 'like', "%{$term}%")
                        ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$term}%"));
                }
            })
            ->latest('published_at')
            ->first()
            ?: Devotional::with('category')->published()->latest('published_at')->first();
    }

    private static function recommendedCategory(array $terms): ?DevotionalCategory
    {
        return DevotionalCategory::query()
            ->where('is_active', true)
            ->where(function ($query) use ($terms): void {
                foreach ($terms as $term) {
                    $query->orWhere('name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                }
            })
            ->first();
    }
}
