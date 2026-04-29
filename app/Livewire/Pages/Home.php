<?php

namespace App\Livewire\Pages;

use App\Models\Devotional;
use App\Models\DevotionalCategory;
use App\Models\Testimony;
use App\Support\DailySpiritualRhythm;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.pages.home', [
            'dailyRhythm' => DailySpiritualRhythm::forDate(),
            'featuredDevotional' => Devotional::with('category')
                ->published()
                ->where('is_featured', true)
                ->latest('published_at')
                ->first(),
            'latestDevotionals' => Devotional::with('category')
                ->published()
                ->latest('published_at')
                ->take(3)
                ->get(),
            'categories' => DevotionalCategory::where('is_active', true)
                ->withCount(['devotionals' => fn ($query) => $query->published()])
                ->orderBy('name')
                ->get(),
            'testimonies' => Testimony::approved()
                ->latest()
                ->take(3)
                ->get(),
        ]);
    }
}
