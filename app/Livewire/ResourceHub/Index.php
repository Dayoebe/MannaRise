<?php

namespace App\Livewire\ResourceHub;

use App\Models\DailyDevotion;
use App\Models\ResourceCategory;
use App\Models\ResourceItem;
use App\Services\ResourceHub\ResourceHubService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $type = '';

    #[Url(except: '')]
    public string $category = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function render(ResourceHubService $service)
    {
        $categories = ResourceCategory::active()->orderBy('name')->get();
        $resources = ResourceItem::query()
            ->with('category')
            ->published()
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($query): void {
                    $query
                        ->where('title', 'like', "%{$this->search}%")
                        ->orWhere('excerpt', 'like', "%{$this->search}%")
                        ->orWhere('author', 'like', "%{$this->search}%");
                });
            })
            ->when($this->type !== '', fn ($query) => $query->where('type', $this->type))
            ->when($this->category !== '', fn ($query) => $query->where('resource_category_id', $this->category))
            ->latest('published_at')
            ->latest()
            ->paginate(9);

        return view('livewire.resource-hub.index', [
            'categories' => $categories,
            'todayDevotion' => $service->todayDevotion() ?: DailyDevotion::published()->latest('devotion_date')->first(),
            'featured' => ResourceItem::with('category')->published()->where('is_featured', true)->latest('published_at')->take(4)->get(),
            'latestBooks' => ResourceItem::published()->type('book')->latest('published_at')->take(4)->get(),
            'latestVideos' => ResourceItem::published()->type('video')->latest('published_at')->take(3)->get(),
            'latestAudio' => ResourceItem::published()->type('audio')->latest('published_at')->take(3)->get(),
            'resources' => $resources,
        ]);
    }
}
