<?php

namespace App\Livewire\Bible;

use App\Models\BibleChapterCompletion;
use App\Models\BibleBook;
use App\Models\BibleVerse;
use App\Models\PersonalizedDailyPathCheckIn;
use App\Models\UserBibleReadingHistory;
use App\Models\UserBibleVerseEngagement;
use App\Support\BibleChapterStudyGuide;
use App\Support\PersonalizedDailyPath;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Reader extends Component
{
    use WithPagination;

    public string $bookSlug = '';

    public int $chapter = 1;

    #[Url(except: 'en')]
    public string $language = 'en';

    #[Url(except: 'KJV')]
    public string $version = 'KJV';

    public string $search = '';

    /**
     * @var array<int, string>
     */
    public array $notes = [];

    public function mount(?string $book = null, ?int $chapter = null): void
    {
        $lastReading = auth()->check() && ! $book
            ? UserBibleReadingHistory::with('book')
                ->where('user_id', auth()->id())
                ->latest('last_read_at')
                ->first()
            : null;
        $firstBook = BibleBook::orderBy('book_order')->first();

        $this->bookSlug = $book ?: ($lastReading?->book?->slug ?: ($firstBook?->slug ?? ''));
        $this->chapter = max(1, $chapter ?: ($lastReading?->chapter ?: 1));
        $this->language = $lastReading && ! $book ? $lastReading->language : $this->language;
        $this->version = $lastReading && ! $book ? $lastReading->version : $this->version;
        $this->normalizeSelection();
        $this->recordReadingHistory();
    }

    public function updatedBookSlug(): void
    {
        $this->chapter = 1;
        $this->resetPage();
        $this->recordReadingHistory();
    }

    public function updatedChapter(): void
    {
        $this->chapter = max(1, (int) $this->chapter);
        $this->resetPage();
        $this->recordReadingHistory();
    }

    public function updatedLanguage(): void
    {
        $this->normalizeSelection();
        $this->resetPage();
        $this->recordReadingHistory();
    }

    public function updatedVersion(): void
    {
        $this->normalizeSelection();
        $this->resetPage();
        $this->recordReadingHistory();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function previousChapter(): void
    {
        $book = $this->selectedBook();

        if (! $book) {
            return;
        }

        if ($this->chapter > 1) {
            $this->chapter--;
            $this->recordReadingHistory();

            return;
        }

        $previousBook = BibleBook::where('book_order', '<', $book->book_order)->orderByDesc('book_order')->first();

        if ($previousBook) {
            $this->bookSlug = $previousBook->slug;
            $this->chapter = $previousBook->chapters;
            $this->recordReadingHistory();
        }
    }

    public function nextChapter(): void
    {
        $book = $this->selectedBook();

        if (! $book) {
            return;
        }

        if ($this->chapter < $book->chapters) {
            $this->chapter++;
            $this->recordReadingHistory();

            return;
        }

        $nextBook = BibleBook::where('book_order', '>', $book->book_order)->orderBy('book_order')->first();

        if ($nextBook) {
            $this->bookSlug = $nextBook->slug;
            $this->chapter = 1;
            $this->recordReadingHistory();
        }
    }

    public function toggleBookmark(int $verseId): void
    {
        abort_unless(auth()->check(), 403);

        $engagement = $this->engagementForVerse($verseId);
        $engagement->bookmarked_at = $engagement->bookmarked_at ? null : now();
        $engagement->save();
    }

    public function setHighlight(int $verseId, ?string $color): void
    {
        abort_unless(auth()->check(), 403);

        $allowed = ['amber', 'emerald', 'sky', 'rose', 'violet'];
        $color = in_array($color, $allowed, true) ? $color : null;

        $engagement = $this->engagementForVerse($verseId);
        $engagement->highlight_color = $engagement->highlight_color === $color ? null : $color;
        $engagement->highlighted_at = $engagement->highlight_color ? now() : null;
        $engagement->save();
    }

    public function saveNote(int $verseId): void
    {
        abort_unless(auth()->check(), 403);

        $note = trim((string) ($this->notes[$verseId] ?? ''));
        $engagement = $this->engagementForVerse($verseId);
        $engagement->note = $note !== '' ? $note : null;
        $engagement->note_updated_at = $engagement->note ? now() : null;
        $engagement->save();

        session()->flash('status', 'Verse note saved.');
    }

    public function recordShare(int $verseId): void
    {
        if (! auth()->check()) {
            return;
        }

        $engagement = $this->engagementForVerse($verseId);
        $engagement->shared_at = now();
        $engagement->save();
    }

    public function markChapterRead(): void
    {
        abort_unless(auth()->check(), 403);

        $book = $this->selectedBook();

        if (! $book) {
            return;
        }

        BibleChapterCompletion::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'bible_book_id' => $book->id,
                'chapter' => $this->chapter,
            ],
            [
                'assigned_on' => today()->toDateString(),
                'source' => 'personal-reader',
                'completed_at' => now(),
            ],
        );

        $this->recordReadingHistory(true);
        $this->completePathScriptureIfMatched($book);

        session()->flash('status', "{$book->name} {$this->chapter} marked as read.");
    }

    private function selectedBook(): ?BibleBook
    {
        return $this->bookSlug !== ''
            ? BibleBook::where('slug', $this->bookSlug)->first()
            : null;
    }

    private function availableBooksForSelection(): Collection
    {
        return BibleBook::query()
            ->whereHas('verses', fn ($query) => $query
                ->where('language', $this->language)
                ->where('version', $this->version))
            ->orderBy('book_order')
            ->get();
    }

    private function chapterCountFor(?BibleBook $book): int
    {
        if (! $book) {
            return 1;
        }

        $availableChapters = BibleVerse::query()
            ->where('bible_book_id', $book->id)
            ->where('language', $this->language)
            ->where('version', $this->version)
            ->max('chapter');

        return max(1, (int) ($availableChapters ?: $book->chapters));
    }

    private function normalizeSelection(): void
    {
        $available = $this->availableTranslations();

        if ($available->isEmpty()) {
            $this->language = 'en';
            $this->version = 'KJV';

            return;
        }

        $selected = $available->first(fn (array $translation) => $translation['language'] === $this->language && $translation['version'] === $this->version);

        if ($selected) {
            return;
        }

        $languageFallback = $available->first(fn (array $translation) => $translation['language'] === $this->language);
        $fallback = $languageFallback ?: $available->first();

        $this->language = $fallback['language'];
        $this->version = $fallback['version'];
    }

    /**
     * @return Collection<int, array{language: string, language_label: string, version: string, label: string, count: int}>
     */
    private function availableTranslations(): Collection
    {
        return BibleVerse::query()
            ->selectRaw('language, version, COUNT(*) as verse_count')
            ->groupBy('language', 'version')
            ->orderBy('language')
            ->orderBy('version')
            ->get()
            ->map(fn (BibleVerse $verse): array => [
                'language' => $verse->language ?: 'en',
                'language_label' => $this->languageLabel($verse->language ?: 'en'),
                'version' => $verse->version,
                'label' => $this->languageLabel($verse->language ?: 'en').' - '.$verse->version,
                'count' => (int) $verse->getAttribute('verse_count'),
            ]);
    }

    private function languageLabel(string $language): string
    {
        return [
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'pt' => 'Portuguese',
            'yo' => 'Yoruba',
            'ig' => 'Igbo',
            'ha' => 'Hausa',
            'sw' => 'Swahili',
        ][$language] ?? strtoupper($language);
    }

    private function engagementForVerse(int $verseId): UserBibleVerseEngagement
    {
        $verse = BibleVerse::whereKey($verseId)->firstOrFail();

        return UserBibleVerseEngagement::firstOrCreate([
            'user_id' => auth()->id(),
            'bible_verse_id' => $verse->id,
        ]);
    }

    private function recordReadingHistory(bool $forceIncrement = false): void
    {
        if (! auth()->check()) {
            return;
        }

        $book = $this->selectedBook();

        if (! $book) {
            return;
        }

        $history = UserBibleReadingHistory::firstOrNew([
            'user_id' => auth()->id(),
            'bible_book_id' => $book->id,
            'chapter' => $this->chapter,
            'language' => $this->language,
            'version' => $this->version,
        ]);

        $lastReadAt = $history->last_read_at;
        $shouldIncrement = $forceIncrement || ! $lastReadAt || $lastReadAt->lt(now()->subMinutes(10));

        $history->read_count = (int) $history->read_count + ($shouldIncrement ? 1 : 0);
        $history->last_read_at = now();
        $history->save();
    }

    private function completePathScriptureIfMatched(BibleBook $book): void
    {
        $profile = auth()->user()?->spiritualProfile()->first();
        $path = PersonalizedDailyPath::forSeason($profile?->season);
        $definition = $path['definition'];

        if (($path['bible_book']?->id !== $book->id) || (int) $definition['chapter'] !== $this->chapter) {
            return;
        }

        PersonalizedDailyPathCheckIn::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'checked_on' => CarbonImmutable::today()->toDateString(),
            ],
            [
                'season_key' => $path['key'],
                'devotional_id' => $path['devotional']?->id,
                'bible_reference' => $definition['reference'],
                'scripture_completed_at' => now(),
            ],
        );
    }

    public function render()
    {
        $translations = $this->availableTranslations();

        if ($translations->isNotEmpty()) {
            $this->normalizeSelection();
        }

        $books = $this->availableBooksForSelection();

        if ($books->isNotEmpty() && ! $books->contains('slug', $this->bookSlug)) {
            $this->bookSlug = (string) $books->first()->slug;
            $this->chapter = 1;
        }

        $book = $this->selectedBook();
        $chapterCount = $this->chapterCountFor($book);

        if ($this->chapter > $chapterCount) {
            $this->chapter = $chapterCount;
        }

        $verses = $book
            ? BibleVerse::where('bible_book_id', $book->id)
                ->where('language', $this->language)
                ->where('version', $this->version)
                ->where('chapter', $this->chapter)
                ->orderBy('verse')
                ->get()
            : collect();

        $engagements = auth()->check() && $verses->isNotEmpty()
            ? UserBibleVerseEngagement::query()
                ->where('user_id', auth()->id())
                ->whereIn('bible_verse_id', $verses->pluck('id'))
                ->get()
                ->keyBy('bible_verse_id')
            : collect();

        foreach ($engagements as $verseId => $engagement) {
            if (! array_key_exists((int) $verseId, $this->notes)) {
                $this->notes[(int) $verseId] = (string) ($engagement->note ?? '');
            }
        }

        $searchResults = trim($this->search) !== ''
            ? BibleVerse::query()
                ->with('book')
                ->where('language', $this->language)
                ->where('version', $this->version)
                ->where('text', 'like', '%'.trim($this->search).'%')
                ->orderBy('bible_book_id')
                ->orderBy('chapter')
                ->orderBy('verse')
                ->paginate(20)
            : null;

        return view('livewire.bible.reader', [
            'books' => $books,
            'book' => $book,
            'verses' => $verses,
            'chapterCount' => $chapterCount,
            'searchResults' => $searchResults,
            'studyGuide' => BibleChapterStudyGuide::build($book, $this->chapter, $verses),
            'engagements' => $engagements,
            'lastReading' => auth()->check()
                ? UserBibleReadingHistory::with('book')
                    ->where('user_id', auth()->id())
                    ->latest('last_read_at')
                    ->first()
                : null,
            'translations' => $translations,
            'languages' => $translations->unique('language')->values(),
            'versions' => $translations->where('language', $this->language)->values(),
            'languageLabel' => $this->languageLabel($this->language),
        ]);
    }
}
