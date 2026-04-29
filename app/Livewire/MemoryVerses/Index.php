<?php

namespace App\Livewire\MemoryVerses;

use App\Models\MemoryVerseProgress;
use App\Support\MemoryVerseChallenge;
use Livewire\Component;

class Index extends Component
{
    public bool $reminder_enabled = false;

    public function mount(): void
    {
        if (auth()->check()) {
            $this->reminder_enabled = (bool) $this->currentProgress()->reminder_enabled;
        }
    }

    public function logPractice()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $progress = $this->currentProgress();
        $progress->increment('practiced_count');

        session()->flash('status', 'Practice logged for this week.');

        return null;
    }

    public function toggleReminder()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $progress = $this->currentProgress();
        $progress->update(['reminder_enabled' => ! $progress->reminder_enabled]);
        $this->reminder_enabled = $progress->fresh()->reminder_enabled;

        session()->flash('status', $this->reminder_enabled ? 'Memory verse reminder enabled.' : 'Memory verse reminder disabled.');

        return null;
    }

    public function complete()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $progress = $this->currentProgress();
        $progress->update([
            'completed_at' => $progress->completed_at ?: now(),
            'practiced_count' => max(1, $progress->practiced_count),
        ]);

        session()->flash('status', 'Memory verse completed. Badge earned.');

        return null;
    }

    public function render()
    {
        $challenge = MemoryVerseChallenge::current();
        $progress = auth()->check() ? $this->currentProgress() : null;
        $completedWeeks = auth()->check()
            ? MemoryVerseProgress::where('user_id', auth()->id())->whereNotNull('completed_at')->count()
            : 0;

        return view('livewire.memory-verses.index', [
            'challenge' => $challenge,
            'progress' => $progress,
            'completedWeeks' => $completedWeeks,
            'badges' => $this->badges($completedWeeks),
            'recentCompletions' => auth()->check()
                ? MemoryVerseProgress::where('user_id', auth()->id())->whereNotNull('completed_at')->latest('completed_at')->take(5)->get()
                : collect(),
        ]);
    }

    private function currentProgress(): MemoryVerseProgress
    {
        $challenge = MemoryVerseChallenge::current();

        return MemoryVerseProgress::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'week_start' => $challenge['week_start'],
            ],
            [
                'bible_verse_id' => $challenge['bible_verse_id'],
                'reference' => $challenge['reference'],
                'verse_text' => $challenge['text'],
                'practiced_count' => 0,
                'reminder_enabled' => false,
            ],
        );
    }

    /**
     * @return array<int, array{label: string, earned: bool}>
     */
    private function badges(int $completedWeeks): array
    {
        return [
            ['label' => 'First verse', 'earned' => $completedWeeks >= 1],
            ['label' => '4 week steady', 'earned' => $completedWeeks >= 4],
            ['label' => '8 week rooted', 'earned' => $completedWeeks >= 8],
            ['label' => '12 week treasury', 'earned' => $completedWeeks >= 12],
        ];
    }
}
