<?php

namespace App\Livewire\PrayerSessions;

use App\Models\PersonalizedDailyPathCheckIn;
use App\Support\DailySpiritualRhythm;
use App\Support\PersonalizedDailyPath;
use Carbon\CarbonImmutable;
use Livewire\Component;

class Index extends Component
{
    public function completePrayer(): void
    {
        abort_unless(auth()->check(), 403);

        $profile = auth()->user()->spiritualProfile()->first();
        $path = PersonalizedDailyPath::forSeason($profile?->season);

        PersonalizedDailyPathCheckIn::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'checked_on' => CarbonImmutable::today()->toDateString(),
            ],
            [
                'season_key' => $path['key'],
                'devotional_id' => $path['devotional']?->id,
                'bible_reference' => $path['definition']['reference'],
                'prayer_completed_at' => now(),
            ],
        );

        session()->flash('status', 'Prayer marked complete for today\'s path.');
    }

    public function render()
    {
        $dailyRhythm = DailySpiritualRhythm::forDate();
        $verse = $dailyRhythm['verse'] ?? null;

        $scripture = $verse
            ? [
                'text' => $verse->text,
                'reference' => "{$verse->book->name} {$verse->chapter}:{$verse->verse} KJV",
            ]
            : [
                'text' => 'Be still, and know that I am God.',
                'reference' => 'Psalm 46:10 KJV',
            ];

        return view('livewire.prayer-sessions.index', [
            'sessions' => [
                '3' => [
                    'minutes' => 3,
                    'name' => '3 minute reset',
                    'scripture' => $scripture,
                    'declaration' => 'I receive the peace of Christ and take the next step with faith.',
                    'steps' => [
                        ['title' => 'Settle', 'seconds' => 30, 'prompt' => 'Breathe slowly and place this moment before God.'],
                        ['title' => 'Scripture', 'seconds' => 45, 'prompt' => 'Read the verse aloud and notice one word that steadies your heart.'],
                        ['title' => 'Silence', 'seconds' => 60, 'prompt' => 'Sit quietly before the Lord without rushing to fill the space.'],
                        ['title' => 'Ask', 'seconds' => 30, 'prompt' => 'Name one request honestly and simply.'],
                        ['title' => 'Declare', 'seconds' => 15, 'prompt' => 'Speak the closing declaration over your day.'],
                    ],
                ],
                '5' => [
                    'minutes' => 5,
                    'name' => '5 minute focus',
                    'scripture' => $scripture,
                    'declaration' => 'God is with me, His word guides me, and His peace guards me.',
                    'steps' => [
                        ['title' => 'Gratitude', 'seconds' => 45, 'prompt' => 'Thank God for three signs of mercy, provision, or strength.'],
                        ['title' => 'Scripture', 'seconds' => 60, 'prompt' => 'Read the verse twice, once slowly and once as a prayer.'],
                        ['title' => 'Confession', 'seconds' => 45, 'prompt' => 'Release hurry, fear, resentment, or any burden you have been carrying alone.'],
                        ['title' => 'Silence', 'seconds' => 90, 'prompt' => 'Rest quietly and listen for the Spirit\'s correction, comfort, or direction.'],
                        ['title' => 'Intercession', 'seconds' => 45, 'prompt' => 'Pray for one person who needs strength today.'],
                        ['title' => 'Declare', 'seconds' => 15, 'prompt' => 'Close by speaking the declaration with faith.'],
                    ],
                ],
                '10' => [
                    'minutes' => 10,
                    'name' => '10 minute covering',
                    'scripture' => $scripture,
                    'declaration' => 'My life, home, work, and relationships are surrendered to the Lordship of Jesus Christ.',
                    'steps' => [
                        ['title' => 'Stillness', 'seconds' => 60, 'prompt' => 'Slow your breathing and become present to God.'],
                        ['title' => 'Worship', 'seconds' => 75, 'prompt' => 'Tell God who He is before telling Him what you need.'],
                        ['title' => 'Scripture', 'seconds' => 90, 'prompt' => 'Read the verse and turn its truth into a personal prayer.'],
                        ['title' => 'Surrender', 'seconds' => 75, 'prompt' => 'Give God the pressure, decision, or concern that has been loudest.'],
                        ['title' => 'Silence', 'seconds' => 150, 'prompt' => 'Be quiet before the Lord. Let attention become worship.'],
                        ['title' => 'Intercession', 'seconds' => 90, 'prompt' => 'Pray over your family, church, city, and anyone the Spirit brings to mind.'],
                        ['title' => 'Obedience', 'seconds' => 45, 'prompt' => 'Ask what one faithful response should look like after this session.'],
                        ['title' => 'Declare', 'seconds' => 15, 'prompt' => 'Speak the closing declaration and leave the session with peace.'],
                    ],
                ],
            ],
        ]);
    }
}
