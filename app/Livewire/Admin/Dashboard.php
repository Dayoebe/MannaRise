<?php

namespace App\Livewire\Admin;

use App\Models\Devotional;
use App\Models\DevotionalCategory;
use App\Models\DevotionalCompletion;
use App\Models\JournalEntry;
use App\Models\PrayerRequest;
use App\Models\Testimony;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'categories' => DevotionalCategory::count(),
                'devotionals' => Devotional::count(),
                'published_devotionals' => Devotional::where('is_published', true)->count(),
                'journal_entries' => JournalEntry::count(),
                'prayer_requests' => PrayerRequest::count(),
                'open_prayers' => PrayerRequest::where('is_answered', false)->count(),
                'pending_testimonies' => Testimony::where('is_approved', false)->count(),
                'completions' => DevotionalCompletion::count(),
            ],
            'recentDevotionals' => Devotional::with('category')->latest()->take(5)->get(),
            'recentPrayerRequests' => PrayerRequest::latest()->take(5)->get(),
        ]);
    }
}
