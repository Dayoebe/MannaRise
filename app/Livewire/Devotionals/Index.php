<?php

namespace App\Livewire\Devotionals;

use App\Models\Devotional;
use App\Models\DevotionalCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $category = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $devotionals = Devotional::query()
            ->with('category')
            ->published()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($query) {
                    $query
                        ->where('title', 'like', "%{$this->search}%")
                        ->orWhere('bible_reference', 'like', "%{$this->search}%")
                        ->orWhere('content', 'like', "%{$this->search}%")
                        ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->category !== '', fn ($query) => $query->whereHas('category', fn ($category) => $category->where('slug', $this->category)))
            ->latest('published_at')
            ->paginate(9);

        return view('livewire.devotionals.index', [
            'devotionals' => $devotionals,
            'categories' => DevotionalCategory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
