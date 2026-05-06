<?php

namespace App\Livewire\Daily;

use App\Models\DailyRhythmCheckIn;
use App\Models\DailyScripture;
use App\Models\MemoryVerseProgress;
use App\Services\Bible\BibleVerseService;
use App\Support\DailySpiritualRhythm;
use App\Support\Toast;
use Carbon\CarbonImmutable;
use Livewire\Component;

class Index extends Component
{
    public function saveDailyScriptureToMemory(): void
    {
        abort_unless(auth()->check(), 403);

        $scripture = DailyScripture::query()->active()->forToday()->first();

        if (! $scripture) {
            Toast::status($this, 'No daily scripture is available to save yet.');

            return;
        }

        $existing = MemoryVerseProgress::query()
            ->where('user_id', auth()->id())
            ->where('reference', $scripture->reference)
            ->where('verse_text', $scripture->text)
            ->first();

        if ($existing) {
            Toast::status($this, 'This scripture is already in your memory verses.');

            return;
        }

        $weekStart = CarbonImmutable::today()->startOfWeek();

        while (MemoryVerseProgress::where('user_id', auth()->id())->whereDate('week_start', $weekStart)->exists()) {
            $weekStart = $weekStart->addWeek();
        }

        MemoryVerseProgress::create([
            'user_id' => auth()->id(),
            'week_start' => $weekStart->toDateString(),
            'reference' => $scripture->reference,
            'verse_text' => $scripture->text,
            'practiced_count' => 0,
            'reminder_enabled' => false,
        ]);

        Toast::status($this, "Today's scripture was saved to memory verses.");
    }

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

        Toast::status($this, 'Bible challenge reading marked complete.');
    }

    public function render(BibleVerseService $bibleVerseService)
    {
        $today = CarbonImmutable::today();
        $catchUpPlan = auth()->check()
            ? DailySpiritualRhythm::catchUpPlanForUser(auth()->user(), $today)
            : null;

        return view('livewire.daily.index', [
            'dailyRhythm' => DailySpiritualRhythm::forDate($today),
            'todayScripture' => $bibleVerseService->todayFromStorageOrFetch(),
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
