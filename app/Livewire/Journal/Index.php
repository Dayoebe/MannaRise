<?php

namespace App\Livewire\Journal;

use App\Models\Devotional;
use App\Models\JournalEntry;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?int $editingId = null;

    public string $devotional_id = '';

    public string $title = '';

    public string $content = '';

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
            'entry_date' => ['required', 'date'],
        ]);

        $payload = [
            ...$validated,
            'devotional_id' => $this->devotional_id !== '' ? $this->devotional_id : null,
            'user_id' => auth()->id(),
        ];

        if ($this->editingId) {
            JournalEntry::where('user_id', auth()->id())->findOrFail($this->editingId)->update($payload);
        } else {
            JournalEntry::create($payload);
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
        $this->entry_date = today()->toDateString();
    }

    public function render()
    {
        return view('livewire.journal.index', [
            'entries' => JournalEntry::with('devotional')
                ->where('user_id', auth()->id())
                ->latest('entry_date')
                ->paginate(8),
            'devotionals' => Devotional::published()->latest('published_at')->get(['id', 'title']),
        ]);
    }
}
