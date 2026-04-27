<?php

namespace App\Livewire\SpiritualLibrary;

use App\Models\SpiritualBook;
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
        return view('livewire.spiritual-library.index', [
            'featuredBooks' => SpiritualBook::where('is_featured', true)->withCount('chapters')->orderBy('title')->get(),
            'books' => SpiritualBook::query()
                ->withCount('chapters')
                ->when($this->search !== '', fn ($query) => $query->where(function ($query) {
                    $query
                        ->where('title', 'like', "%{$this->search}%")
                        ->orWhere('author', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                }))
                ->orderByDesc('is_featured')
                ->orderBy('title')
                ->paginate(12),
        ]);
    }
}
