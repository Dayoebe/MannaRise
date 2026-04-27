<?php

namespace App\Livewire\SpiritualLibrary;

use App\Models\SpiritualBook;
use Livewire\Component;

class Show extends Component
{
    public SpiritualBook $book;

    public int $chapter = 1;

    public function mount(string $slug, ?int $chapter = null): void
    {
        $this->book = SpiritualBook::with('chapters')->where('slug', $slug)->firstOrFail();
        $this->chapter = max(1, $chapter ?: 1);
    }

    public function previousChapter(): void
    {
        if ($this->chapter > 1) {
            $this->chapter--;
        }
    }

    public function nextChapter(): void
    {
        if ($this->chapter < $this->book->chapters()->count()) {
            $this->chapter++;
        }
    }

    public function render()
    {
        $chapter = $this->book->chapters()
            ->where('chapter_number', $this->chapter)
            ->first()
            ?? $this->book->chapters()->orderBy('chapter_number')->first();

        if ($chapter) {
            $this->chapter = $chapter->chapter_number;
        }

        return view('livewire.spiritual-library.show', [
            'chapters' => $this->book->chapters,
            'currentChapter' => $chapter,
        ]);
    }
}
