<?php

namespace App\Livewire\ResourceHub;

use App\Models\ResourceItem;
use App\Services\ResourceHub\ResourceHubService;
use App\Support\Toast;
use Livewire\Component;
use Livewire\WithPagination;

class Books extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function importExternal(ResourceHubService $service): void
    {
        abort_unless(auth()->user()?->hasAdminAccess(), 403);

        $query = trim($this->search) ?: config('resourcehub.book_keywords.0', 'christian faith');
        $count = $service->import('gutendex', $query, ['limit' => 8])->count()
            + $service->import('open_library', $query, ['limit' => 8])->count();

        Toast::status($this, "{$count} book resources refreshed.");
    }

    public function render()
    {
        return view('livewire.resource-hub.books', [
            'books' => ResourceItem::published()
                ->type('book')
                ->when($this->search !== '', function ($query): void {
                    $query->where(fn ($query) => $query->where('title', 'like', "%{$this->search}%")->orWhere('author', 'like', "%{$this->search}%"));
                })
                ->latest('published_at')
                ->paginate(12),
        ]);
    }
}
