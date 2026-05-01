<?php

namespace App\Livewire\ResourceHub;

use App\Models\DailyDevotion as DailyDevotionModel;
use App\Models\UserResourceBookmark;
use App\Services\ResourceHub\ResourceHubService;
use Livewire\Component;

class DailyDevotion extends Component
{
    public DailyDevotionModel $devotion;

    public function mount(?string $slug = null, ResourceHubService $service): void
    {
        $this->devotion = $slug
            ? DailyDevotionModel::published()->where('slug', $slug)->firstOrFail()
            : ($service->todayDevotion() ?: $service->prepareTodayDevotion());
    }

    public function toggleBookmark(): void
    {
        abort_unless(auth()->check(), 403);

        $bookmark = UserResourceBookmark::where('user_id', auth()->id())
            ->where('daily_devotion_id', $this->devotion->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            session()->flash('status', 'Devotion removed from bookmarks.');
            return;
        }

        UserResourceBookmark::create([
            'user_id' => auth()->id(),
            'daily_devotion_id' => $this->devotion->id,
        ]);

        session()->flash('status', 'Devotion bookmarked.');
    }

    public function render()
    {
        return view('livewire.resource-hub.daily-devotion', [
            'previous' => DailyDevotionModel::published()
                ->whereDate('devotion_date', '<', $this->devotion->devotion_date)
                ->latest('devotion_date')
                ->first(),
            'next' => DailyDevotionModel::published()
                ->whereDate('devotion_date', '>', $this->devotion->devotion_date)
                ->oldest('devotion_date')
                ->first(),
            'isBookmarked' => auth()->check()
                && UserResourceBookmark::where('user_id', auth()->id())->where('daily_devotion_id', $this->devotion->id)->exists(),
        ]);
    }
}
