<?php

namespace App\Support;

use App\Models\BibleBook;
use App\Models\BibleVerse;
use App\Models\Devotional;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BibleChapterStudyGuide
{
    /**
     * @param  Collection<int, BibleVerse>  $verses
     * @return array<string, mixed>
     */
    public static function build(?BibleBook $book, int $chapter, Collection $verses): array
    {
        $text = Str::lower($verses->pluck('text')->join(' '));
        $themes = self::themes($text, $book);
        $reference = trim(($book?->name ?? 'This chapter').' '.$chapter);

        return [
            'summary' => self::summary($book, $chapter, $themes, $verses),
            'themes' => $themes,
            'prayer_points' => self::prayerPoints($themes, $reference),
            'reflection_questions' => self::reflectionQuestions($themes, $reference),
            'related_devotionals' => self::relatedDevotionals($themes, $book),
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function themes(string $text, ?BibleBook $book): array
    {
        $themeMap = [
            'Faith and trust' => ['faith', 'believe', 'trust', 'promise', 'hope'],
            'Prayer and dependence' => ['pray', 'prayer', 'ask', 'seek', 'cry', 'supplication'],
            'Peace and courage' => ['peace', 'fear', 'afraid', 'courage', 'comfort', 'rest'],
            'Obedience and holiness' => ['command', 'obey', 'walk', 'righteous', 'holy', 'sin'],
            'Wisdom and discernment' => ['wisdom', 'understanding', 'knowledge', 'counsel', 'teach'],
            'Love and relationships' => ['love', 'mercy', 'forgive', 'brother', 'family', 'kindness'],
            'Provision and stewardship' => ['bread', 'provide', 'work', 'rich', 'poor', 'harvest'],
            'Healing and restoration' => ['heal', 'restore', 'deliver', 'save', 'broken', 'strength'],
            'Worship and gratitude' => ['praise', 'worship', 'thanks', 'sing', 'glory', 'bless'],
            'Purpose and calling' => ['called', 'sent', 'work', 'serve', 'kingdom', 'fruit'],
        ];

        $matches = collect($themeMap)
            ->mapWithKeys(fn (array $terms, string $theme) => [
                $theme => collect($terms)->sum(fn (string $term) => Str::contains($text, $term) ? 1 : 0),
            ])
            ->filter()
            ->sortDesc()
            ->keys()
            ->take(4)
            ->values()
            ->all();

        if ($matches !== []) {
            return $matches;
        }

        return $book?->testament === 'Old Testament'
            ? ['God\'s faithfulness', 'Covenant and obedience', 'Wisdom for daily life']
            : ['Life in Christ', 'Faith and obedience', 'Grace for daily living'];
    }

    /**
     * @param  array<int, string>  $themes
     * @param  Collection<int, BibleVerse>  $verses
     */
    private static function summary(?BibleBook $book, int $chapter, array $themes, Collection $verses): string
    {
        $reference = trim(($book?->name ?? 'This chapter').' '.$chapter);
        $firstVerse = Str::limit($verses->first()?->text ?? '', 90);
        $themeText = collect($themes)->take(2)->map(fn (string $theme) => Str::lower($theme))->join(' and ');

        if ($firstVerse !== '') {
            return "{$reference} draws attention to {$themeText}. Read it slowly as a chapter that invites trust, response, and a practical step of obedience before God.";
        }

        return "{$reference} is ready for study once Bible verses are available for this translation.";
    }

    /**
     * @param  array<int, string>  $themes
     * @return array<int, string>
     */
    private static function prayerPoints(array $themes, string $reference): array
    {
        return collect($themes)->take(4)->map(fn (string $theme) => match ($theme) {
            'Faith and trust' => "Ask God to strengthen your trust where {$reference} exposes fear, delay, or uncertainty.",
            'Prayer and dependence' => 'Bring one real need before God without pretending you can carry it alone.',
            'Peace and courage' => 'Pray for the peace of Christ to rule your thoughts and the courage to obey calmly.',
            'Obedience and holiness' => 'Ask the Holy Spirit to show one area where obedience needs to become practical today.',
            'Wisdom and discernment' => 'Ask God for wisdom that shapes your words, decisions, and timing.',
            'Love and relationships' => 'Pray for patience, forgiveness, and practical love toward the people nearest to you.',
            'Provision and stewardship' => 'Ask God to align your work, resources, and responsibilities with faithful stewardship.',
            'Healing and restoration' => 'Invite God into the wounded place and ask Him to restore what has grown weary.',
            'Worship and gratitude' => 'Turn the chapter into praise by naming what God reveals about His character.',
            default => 'Ask God to make this chapter personal, clear, and fruitful in your life today.',
        })->values()->all();
    }

    /**
     * @param  array<int, string>  $themes
     * @return array<int, string>
     */
    private static function reflectionQuestions(array $themes, string $reference): array
    {
        $questions = collect($themes)->take(4)->map(fn (string $theme) => match ($theme) {
            'Faith and trust' => 'Where is God asking me to trust Him before I see the full outcome?',
            'Prayer and dependence' => 'What burden have I been carrying that should become prayer?',
            'Peace and courage' => 'What would peace-filled obedience look like today?',
            'Obedience and holiness' => 'Which instruction or conviction needs a concrete response?',
            'Wisdom and discernment' => 'What decision needs God\'s wisdom more than my impulse?',
            'Love and relationships' => 'Who needs love, patience, truth, or forgiveness from me?',
            'Provision and stewardship' => 'What has God already placed in my hand to steward faithfully?',
            'Healing and restoration' => 'What pain or disappointment do I need to bring honestly before God?',
            'Worship and gratitude' => 'What does this chapter reveal about God that I can praise Him for?',
            default => 'What is one truth from this chapter I should carry into the rest of the day?',
        })->values();

        return $questions
            ->push("What verse from {$reference} should I return to in prayer?")
            ->unique()
            ->take(5)
            ->values()
            ->all();
    }

    /**
     * @param  array<int, string>  $themes
     * @return Collection<int, Devotional>
     */
    private static function relatedDevotionals(array $themes, ?BibleBook $book): Collection
    {
        $terms = collect($themes)
            ->flatMap(fn (string $theme) => explode(' ', Str::lower(str_replace([' and ', '&'], ' ', $theme))))
            ->push(Str::lower($book?->name ?? ''))
            ->filter(fn (string $term) => strlen($term) > 3)
            ->unique()
            ->values();

        return Devotional::query()
            ->with('category')
            ->published()
            ->where(function ($query) use ($terms): void {
                foreach ($terms as $term) {
                    $query->orWhere('title', 'like', "%{$term}%")
                        ->orWhere('content', 'like', "%{$term}%")
                        ->orWhere('bible_reference', 'like', "%{$term}%")
                        ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$term}%"));
                }
            })
            ->latest('published_at')
            ->take(3)
            ->get();
    }
}
