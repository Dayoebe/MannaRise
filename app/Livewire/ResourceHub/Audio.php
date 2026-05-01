<?php

namespace App\Livewire\ResourceHub;

use App\Models\ResourceItem;
use App\Services\ResourceHub\ResourceHubService;
use Livewire\Component;
use Livewire\WithPagination;

class Audio extends Component
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

        $query = trim($this->search) ?: config('resourcehub.audio_keywords.0', 'bible');
        $count = $service->import('librivox', $query, ['limit' => 10])->count();

        session()->flash('status', "{$count} audio resources refreshed.");
    }

    public function render()
    {
        return view('livewire.resource-hub.audio', [
            'audioItems' => ResourceItem::published()
                ->whereIn('type', ['audio', 'sermon'])
                ->when($this->search !== '', function ($query): void {
                    $query->where(fn ($query) => $query->where('title', 'like', "%{$this->search}%")->orWhere('author', 'like', "%{$this->search}%"));
                })
                ->latest('published_at')
                ->paginate(10),
        ]);
    }
}
