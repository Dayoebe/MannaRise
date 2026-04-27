<?php

namespace App\Livewire\AudioDevotionals;

use App\Models\AudioDevotional;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.audio-devotionals.index', [
            'audioDevotionals' => AudioDevotional::query()
                ->with('devotional')
                ->published()
                ->when($this->search !== '', fn ($query) => $query->where('title', 'like', "%{$this->search}%"))
                ->latest('published_at')
                ->paginate(9),
        ]);
    }
}
