<?php

namespace App\Livewire\ResourceHub;

use App\Models\ResourceItem;
use App\Services\ResourceHub\ResourceHubService;
use Livewire\Component;
use Livewire\WithPagination;

class Videos extends Component
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

        $query = trim($this->search) ?: config('resourcehub.youtube_keywords.0', 'Christian devotional');
        $count = $service->import('youtube', $query, ['limit' => 8])->count();

        session()->flash('status', $count > 0 ? "{$count} video resources refreshed." : 'Add a YouTube API key to fetch external videos.');
    }

    public function render()
    {
        return view('livewire.resource-hub.videos', [
            'videos' => ResourceItem::published()
                ->type('video')
                ->when($this->search !== '', function ($query): void {
                    $query->where(fn ($query) => $query->where('title', 'like', "%{$this->search}%")->orWhere('author', 'like', "%{$this->search}%"));
                })
                ->latest('published_at')
                ->paginate(9),
        ]);
    }
}
