<?php

namespace App\Livewire\Bible;

use App\Models\BibleBook;
use App\Models\BibleVerse;
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

    public function mount(?string $book = null, ?int $chapter = null): void
    {
        $firstBook = BibleBook::orderBy('book_order')->first();

        $this->bookSlug = $book ?: ($firstBook?->slug ?? '');
        $this->chapter = max(1, $chapter ?: 1);
        $this->normalizeSelection();
    }

    public function updatedBookSlug(): void
    {
        $this->chapter = 1;
        $this->resetPage();
    }

    public function updatedChapter(): void
    {
        $this->chapter = max(1, (int) $this->chapter);
        $this->resetPage();
    }

    public function updatedLanguage(): void
    {
        $this->normalizeSelection();
        $this->resetPage();
    }

    public function updatedVersion(): void
    {
        $this->normalizeSelection();
        $this->resetPage();
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

            return;
        }

        $previousBook = BibleBook::where('book_order', '<', $book->book_order)->orderByDesc('book_order')->first();

        if ($previousBook) {
            $this->bookSlug = $previousBook->slug;
            $this->chapter = $previousBook->chapters;
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

            return;
        }

        $nextBook = BibleBook::where('book_order', '>', $book->book_order)->orderBy('book_order')->first();

        if ($nextBook) {
            $this->bookSlug = $nextBook->slug;
            $this->chapter = 1;
        }
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
            'translations' => $translations,
            'languages' => $translations->unique('language')->values(),
            'versions' => $translations->where('language', $this->language)->values(),
            'languageLabel' => $this->languageLabel($this->language),
        ]);
    }
}
