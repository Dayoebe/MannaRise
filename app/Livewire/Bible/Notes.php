<?php

namespace App\Livewire\Bible;

use App\Models\UserBibleVerseEngagement;
use Livewire\Component;
use Livewire\WithPagination;

class Notes extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function deleteNote(int $id): void
    {
        $engagement = UserBibleVerseEngagement::where('user_id', auth()->id())->findOrFail($id);
        $engagement->update([
            'note' => null,
            'note_updated_at' => null,
        ]);

        session()->flash('status', 'Bible note removed.');
    }

    public function render()
    {
        $query = UserBibleVerseEngagement::query()
            ->with('verse.book')
            ->where('user_id', auth()->id())
            ->where(function ($query): void {
                $query->whereNotNull('note')
                    ->orWhereNotNull('bookmarked_at')
                    ->orWhereNotNull('highlighted_at');
            });

        match ($this->filter) {
            'notes' => $query->whereNotNull('note'),
            'bookmarks' => $query->whereNotNull('bookmarked_at'),
            'highlights' => $query->whereNotNull('highlighted_at'),
            default => null,
        };

        return view('livewire.bible.notes', [
            'engagements' => $query->latest('updated_at')->paginate(12),
            'counts' => [
                'all' => UserBibleVerseEngagement::where('user_id', auth()->id())
                    ->where(fn ($query) => $query->whereNotNull('note')->orWhereNotNull('bookmarked_at')->orWhereNotNull('highlighted_at'))
                    ->count(),
                'notes' => UserBibleVerseEngagement::where('user_id', auth()->id())->whereNotNull('note')->count(),
                'bookmarks' => UserBibleVerseEngagement::where('user_id', auth()->id())->whereNotNull('bookmarked_at')->count(),
                'highlights' => UserBibleVerseEngagement::where('user_id', auth()->id())->whereNotNull('highlighted_at')->count(),
            ],
        ]);
    }
}
