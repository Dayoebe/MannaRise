<?php

namespace App\Livewire\Journal;

use App\Models\Devotional;
use App\Models\JournalEntry;
use App\Models\PersonalizedDailyPathCheckIn;
use App\Models\PrayerRequest;
use App\Support\PersonalizedDailyPath;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?int $editingId = null;

    public string $devotional_id = '';

    public string $title = '';

    public string $content = '';

    public string $mood = '';

    public string $topicsInput = '';

    public string $entry_date = '';

    public function mount(): void
    {
        $this->entry_date = today()->toDateString();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'devotional_id' => ['nullable', 'exists:devotionals,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
            'mood' => ['nullable', 'string', 'max:60'],
            'topicsInput' => ['nullable', 'string', 'max:255'],
            'entry_date' => ['required', 'date'],
        ]);

        $payload = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'mood' => $validated['mood'] ?: null,
            'topics' => $this->normalizedTopics($validated['topicsInput'] ?? ''),
            'entry_date' => $validated['entry_date'],
            'devotional_id' => $this->devotional_id !== '' ? $this->devotional_id : null,
            'user_id' => auth()->id(),
        ];

        if ($this->editingId) {
            JournalEntry::where('user_id', auth()->id())->findOrFail($this->editingId)->update($payload);
        } else {
            JournalEntry::create($payload);
        }

        if (CarbonImmutable::parse($payload['entry_date'])->isSameDay(today())) {
            $this->markPathJournalComplete();
        }

        $this->resetForm();
        session()->flash('status', 'Journal entry saved.');
    }

    public function edit(int $id): void
    {
        $entry = JournalEntry::where('user_id', auth()->id())->findOrFail($id);

        $this->editingId = $entry->id;
        $this->devotional_id = (string) ($entry->devotional_id ?? '');
        $this->title = $entry->title;
        $this->content = $entry->content;
        $this->mood = (string) ($entry->mood ?? '');
        $this->topicsInput = collect($entry->topics ?? [])->join(', ');
        $this->entry_date = $entry->entry_date->toDateString();
    }

    public function delete(int $id): void
    {
        JournalEntry::where('user_id', auth()->id())->findOrFail($id)->delete();
        session()->flash('status', 'Journal entry deleted.');
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->devotional_id = '';
        $this->title = '';
        $this->content = '';
        $this->mood = '';
        $this->topicsInput = '';
        $this->entry_date = today()->toDateString();
    }

    private function normalizedTopics(string $topics): array
    {
        return collect(explode(',', $topics))
            ->map(fn (string $topic) => trim($topic))
            ->filter()
            ->unique()
            ->take(8)
            ->values()
            ->all();
    }

    private function markPathJournalComplete(): void
    {
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
                'journal_completed_at' => now(),
            ],
        );
    }

    public function render()
    {
        return view('livewire.journal.index', [
            'entries' => JournalEntry::with('devotional')
                ->where('user_id', auth()->id())
                ->latest('entry_date')
                ->paginate(8),
            'devotionals' => Devotional::published()->latest('published_at')->get(['id', 'title']),
            'insights' => $this->insights(),
        ]);
    }

    private function insights(): array
    {
        $monthStart = today()->startOfMonth();
        $entries = JournalEntry::query()
            ->where('user_id', auth()->id())
            ->whereDate('entry_date', '>=', $monthStart)
            ->get();

        $topicCounts = $entries
            ->flatMap(fn (JournalEntry $entry) => collect($entry->topics ?? []))
            ->map(fn ($topic) => Str::lower(trim((string) $topic)))
            ->filter()
            ->countBy()
            ->sortDesc();

        $moodCounts = $entries
            ->pluck('mood')
            ->map(fn ($mood) => Str::lower(trim((string) $mood)))
            ->filter()
            ->countBy()
            ->sortDesc();

        $prayerText = PrayerRequest::query()
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', $monthStart)
            ->get(['title', 'body'])
            ->map(fn (PrayerRequest $request) => Str::lower($request->title.' '.$request->body))
            ->join(' ');

        $prayerTopics = collect(['family', 'healing', 'peace', 'business', 'marriage', 'exams', 'salvation', 'grief', 'purpose', 'faith'])
            ->mapWithKeys(fn (string $topic) => [$topic => substr_count($prayerText, $topic)])
            ->filter()
            ->sortDesc();

        $topTopic = $topicCounts->keys()->first();
        $topMood = $moodCounts->keys()->first();
        $topPrayerTopic = $prayerTopics->keys()->first();
        $topPrayerCount = $topPrayerTopic ? $prayerTopics[$topPrayerTopic] : 0;

        return [
            'entry_count' => $entries->count(),
            'topic_summary' => $topTopic
                ? 'You wrote most about '.str($topTopic)->headline()->lower().' this month.'
                : 'Add topics to your journal entries and patterns will appear here.',
            'mood_summary' => $topMood
                ? 'Your most common journal mood this month is '.str($topMood)->headline()->lower().'.'
                : 'Add a mood to each reflection to see emotional patterns over time.',
            'prayer_summary' => $topPrayerTopic
                ? 'You prayed about '.str($topPrayerTopic)->headline()->lower().' '.$topPrayerCount.' '.Str::plural('time', $topPrayerCount).' this month.'
                : 'Prayer themes will appear when your requests mention topics like family, peace, faith, or healing.',
            'top_topics' => $topicCounts->take(5),
            'top_moods' => $moodCounts->take(5),
        ];
    }
}
