<?php

namespace App\Livewire\Daily;

use App\Models\DailyRhythmCheckIn;
use App\Support\DailySpiritualRhythm;
use Carbon\CarbonImmutable;
use Livewire\Component;

class Index extends Component
{
    public function markVerseComplete(): void
    {
        abort_unless(auth()->check(), 403);

        $rhythm = DailySpiritualRhythm::forDate();
        $verse = $rhythm['verse'];

        $this->checkIn()->update([
            'verse_reference' => $verse ? "{$verse->book->name} {$verse->chapter}:{$verse->verse}" : null,
            'verse_completed_at' => now(),
        ]);
    }

    public function markAffirmationComplete(): void
    {
        abort_unless(auth()->check(), 403);

        $rhythm = DailySpiritualRhythm::forDate();

        $this->checkIn()->update([
            'affirmation_reference' => $rhythm['affirmation']['reference'],
            'affirmation_completed_at' => now(),
        ]);
    }

    public function completeChallenge(): void
    {
        abort_unless(auth()->check(), 403);

        $user = auth()->user();
        $today = CarbonImmutable::today();
        $catchUpPlan = DailySpiritualRhythm::catchUpPlanForUser($user, $today);

        if (! $catchUpPlan) {
            return;
        }

        DailySpiritualRhythm::completeReadingsForUser($user, $catchUpPlan['readings'], $today);

        $this->checkIn()->update([
            'bible_reading_label' => $catchUpPlan['reading_label'],
            'challenge_completed_at' => now(),
        ]);

        session()->flash('status', 'Bible challenge reading marked complete.');
    }

    public function render()
    {
        $today = CarbonImmutable::today();
        $catchUpPlan = auth()->check()
            ? DailySpiritualRhythm::catchUpPlanForUser(auth()->user(), $today)
            : null;

        return view('livewire.daily.index', [
            'dailyRhythm' => DailySpiritualRhythm::forDate($today),
            'upcomingPlans' => DailySpiritualRhythm::challengePreview($today, 7),
            'catchUpPlan' => $catchUpPlan,
            'checkIn' => auth()->check() ? $this->checkIn(false) : null,
        ]);
    }

    private function checkIn(bool $create = true): ?DailyRhythmCheckIn
    {
        $query = DailyRhythmCheckIn::query()
            ->where('user_id', auth()->id())
            ->whereDate('checked_on', CarbonImmutable::today());

        if (! $create) {
            return $query->first();
        }

        return DailyRhythmCheckIn::firstOrCreate([
            'user_id' => auth()->id(),
            'checked_on' => CarbonImmutable::today()->toDateString(),
        ]);
    }
}
