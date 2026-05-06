<?php

namespace App\Providers;

use App\Models\BibleChapterCompletion;
use App\Models\CommunityGroupMembership;
use App\Models\CommunityGroupPrayer;
use App\Models\CommunityGroupReadingLog;
use App\Models\DailyRhythmCheckIn;
use App\Models\DailyScripture;
use App\Models\DevotionalCompletion;
use App\Models\DevotionalFavorite;
use App\Models\DevotionalPlanCompletion;
use App\Models\JournalEntry;
use App\Models\MemoryVerseProgress;
use App\Models\PrayerRequest;
use App\Models\PrayerRequestUpdate;
use App\Models\PrayerRoomPrayer;
use App\Models\Testimony;
use App\Models\UserBibleReadingHistory;
use App\Models\UserBibleVerseEngagement;
use App\Models\UserResourceBookmark;
use App\Models\UserResourceProgress;
use App\Observers\ActivityAlertObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ([
            BibleChapterCompletion::class,
            CommunityGroupMembership::class,
            CommunityGroupPrayer::class,
            CommunityGroupReadingLog::class,
            DailyRhythmCheckIn::class,
            DailyScripture::class,
            DevotionalCompletion::class,
            DevotionalFavorite::class,
            DevotionalPlanCompletion::class,
            JournalEntry::class,
            MemoryVerseProgress::class,
            PrayerRequest::class,
            PrayerRequestUpdate::class,
            PrayerRoomPrayer::class,
            Testimony::class,
            UserBibleReadingHistory::class,
            UserBibleVerseEngagement::class,
            UserResourceBookmark::class,
            UserResourceProgress::class,
        ] as $model) {
            $model::observe(ActivityAlertObserver::class);
        }
    }
}
