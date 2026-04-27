<?php

namespace App\Livewire\Bible;

use App\Models\BibleBook;
use App\Models\BibleVerse;
use Livewire\Component;
use Livewire\WithPagination;

class Reader extends Component
{
    use WithPagination;

    public string $bookSlug = '';

    public int $chapter = 1;

    public string $search = '';

    public function mount(?string $book = null, ?int $chapter = null): void
    {
        $firstBook = BibleBook::orderBy('book_order')->first();

        $this->bookSlug = $book ?: ($firstBook?->slug ?? '');
        $this->chapter = max(1, $chapter ?: 1);
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

    public function render()
    {
        $books = BibleBook::orderBy('book_order')->get();
        $book = $this->selectedBook();

        if ($book && $this->chapter > $book->chapters) {
            $this->chapter = $book->chapters;
        }

        $verses = $book
            ? BibleVerse::where('bible_book_id', $book->id)
                ->where('chapter', $this->chapter)
                ->orderBy('verse')
                ->get()
            : collect();

        $searchResults = trim($this->search) !== ''
            ? BibleVerse::query()
                ->with('book')
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
            'searchResults' => $searchResults,
        ]);
    }
}
