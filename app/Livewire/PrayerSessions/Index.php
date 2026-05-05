<?php

namespace App\Livewire\PrayerSessions;

use App\Models\PersonalizedDailyPathCheckIn;
use App\Support\DailySpiritualRhythm;
use App\Support\PersonalizedDailyPath;
use Carbon\CarbonImmutable;
use Livewire\Component;

class Index extends Component
{
    private function scripture(): array
    {
        $dailyRhythm = DailySpiritualRhythm::forDate();
        $verse = $dailyRhythm['verse'] ?? null;

        return $verse
            ? [
                'text' => $verse->text,
                'reference' => "{$verse->book->name} {$verse->chapter}:{$verse->verse} KJV",
            ]
            : [
                'text' => 'Be still, and know that I am God.',
                'reference' => 'Psalm 46:10 KJV',
            ];
    }

    private function sessions(array $scripture): array
    {
        return [
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
            '30' => [
                'minutes' => 30,
                'name' => '30 minute deep prayer',
                'scripture' => $scripture,
                'declaration' => 'The Lord renews my strength, orders my steps, and teaches me to abide in His presence.',
                'steps' => [
                    ['title' => 'Arrival', 'seconds' => 120, 'prompt' => 'Set down distractions, breathe slowly, and welcome the nearness of the Father.'],
                    ['title' => 'Adoration', 'seconds' => 180, 'prompt' => 'Worship God for His holiness, mercy, faithfulness, wisdom, and unfailing love.'],
                    ['title' => 'Scripture meditation', 'seconds' => 300, 'prompt' => 'Read the verse slowly, repeat it, and let one phrase become prayer.'],
                    ['title' => 'Thanksgiving', 'seconds' => 240, 'prompt' => 'Name specific gifts, answered prayers, preserved moments, and quiet mercies.'],
                    ['title' => 'Confession', 'seconds' => 180, 'prompt' => 'Bring sin, hurry, resentment, pride, fear, or hidden heaviness into God\'s light.'],
                    ['title' => 'Listening silence', 'seconds' => 240, 'prompt' => 'Sit quietly before the Lord and let your attention rest on Him without striving.'],
                    ['title' => 'Personal petition', 'seconds' => 180, 'prompt' => 'Ask God for wisdom, provision, healing, courage, and grace for the day ahead.'],
                    ['title' => 'Intercession', 'seconds' => 180, 'prompt' => 'Pray over family, friends, church, city, leaders, and anyone the Spirit brings to mind.'],
                    ['title' => 'Surrender', 'seconds' => 120, 'prompt' => 'Place outcomes, timing, decisions, and relationships under the Lordship of Jesus.'],
                    ['title' => 'Declaration', 'seconds' => 60, 'prompt' => 'Speak the closing declaration slowly and receive the peace of Christ.'],
                ],
            ],
            '60' => [
                'minutes' => 60,
                'name' => '1 hour watch',
                'scripture' => $scripture,
                'declaration' => 'My heart is fixed on the Lord; I will watch, pray, listen, obey, and walk in His peace.',
                'steps' => [
                    ['title' => 'Consecration', 'seconds' => 300, 'prompt' => 'Offer your body, mind, emotions, plans, and attention to God as living worship.'],
                    ['title' => 'Adoration', 'seconds' => 360, 'prompt' => 'Magnify God for who He is before presenting what you need from Him.'],
                    ['title' => 'Scripture reading', 'seconds' => 360, 'prompt' => 'Read the verse and related passage slowly, asking the Spirit to make the word alive.'],
                    ['title' => 'Meditation', 'seconds' => 360, 'prompt' => 'Stay with one phrase from Scripture and turn it over in prayer, worship, and surrender.'],
                    ['title' => 'Thanksgiving', 'seconds' => 300, 'prompt' => 'Thank God for provision, protection, relationships, growth, correction, and unseen help.'],
                    ['title' => 'Confession and cleansing', 'seconds' => 300, 'prompt' => 'Let the Lord search your heart and receive forgiveness with honesty and humility.'],
                    ['title' => 'Listening silence', 'seconds' => 480, 'prompt' => 'Rest quietly before God, noticing what He brings to attention without forcing an answer.'],
                    ['title' => 'Personal petition', 'seconds' => 300, 'prompt' => 'Pray about decisions, needs, burdens, healing, direction, discipline, and daily obedience.'],
                    ['title' => 'Family and relationships', 'seconds' => 240, 'prompt' => 'Cover your household, friendships, mentors, church family, and strained relationships in prayer.'],
                    ['title' => 'Kingdom intercession', 'seconds' => 240, 'prompt' => 'Pray for salvation, justice, leaders, the vulnerable, your city, and the work of the gospel.'],
                    ['title' => 'Surrender and obedience', 'seconds' => 180, 'prompt' => 'Ask what needs to be released, repaired, started, stopped, or obeyed after this hour.'],
                    ['title' => 'Blessing and declaration', 'seconds' => 180, 'prompt' => 'Speak the declaration, bless the day, and close with confidence in God\'s presence.'],
                ],
            ],
        ];
    }

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
        $scripture = $this->scripture();

        return view('livewire.prayer-sessions.index', [
            'sessions' => $this->sessions($scripture),
        ]);
    }
}
