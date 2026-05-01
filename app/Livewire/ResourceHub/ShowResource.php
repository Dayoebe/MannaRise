<?php

namespace App\Livewire\ResourceHub;

use App\Models\ResourceItem;
use App\Models\UserResourceBookmark;
use App\Models\UserResourceProgress;
use Livewire\Component;

class ShowResource extends Component
{
    public ResourceItem $resource;

    public int $progressValue = 0;

    public function mount(string $slug): void
    {
        $this->resource = ResourceItem::with('category')
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        if (auth()->check()) {
            $progress = UserResourceProgress::where('user_id', auth()->id())
                ->where('resource_item_id', $this->resource->id)
                ->first();

            $this->progressValue = $progress?->progress_value ?? 0;
            $this->touchProgress();
        }
    }

    public function toggleBookmark(): void
    {
        abort_unless(auth()->check(), 403);

        $bookmark = UserResourceBookmark::where('user_id', auth()->id())
            ->where('resource_item_id', $this->resource->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            session()->flash('status', 'Resource removed from bookmarks.');
            return;
        }

        UserResourceBookmark::create([
            'user_id' => auth()->id(),
            'resource_item_id' => $this->resource->id,
        ]);

        session()->flash('status', 'Resource bookmarked.');
    }

    public function updateProgress(): void
    {
        abort_unless(auth()->check(), 403);

        $this->progressValue = max(0, min(100, $this->progressValue));
        $this->touchProgress();
        session()->flash('status', 'Progress saved.');
    }

    private function touchProgress(): void
    {
        if (! auth()->check()) {
            return;
        }

        UserResourceProgress::updateOrCreate(
            ['user_id' => auth()->id(), 'resource_item_id' => $this->resource->id],
            [
                'progress_type' => match ($this->resource->type) {
                    'video' => 'watching',
                    'audio', 'sermon' => 'listening',
                    default => 'reading',
                },
                'progress_value' => $this->progressValue,
                'completed_at' => $this->progressValue >= 100 ? now() : null,
                'last_accessed_at' => now(),
            ],
        );
    }

    public function render()
    {
        return view('livewire.resource-hub.show-resource', [
            'isBookmarked' => auth()->check()
                && UserResourceBookmark::where('user_id', auth()->id())->where('resource_item_id', $this->resource->id)->exists(),
            'related' => ResourceItem::published()
                ->whereKeyNot($this->resource->id)
                ->where('type', $this->resource->type)
                ->latest('published_at')
                ->take(4)
                ->get(),
        ]);
    }
}
