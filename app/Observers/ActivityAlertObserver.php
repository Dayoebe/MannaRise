<?php

namespace App\Observers;

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
use App\Support\ActivityAlerts;
use Illuminate\Database\Eloquent\Model;

class ActivityAlertObserver
{
    public function created(Model $model): void
    {
        match (true) {
            $model instanceof JournalEntry => ActivityAlerts::notifyOwner($model, 'journal.created', 'Journal reflection saved', 'Your reflection was added to your spiritual journal.', route('journal.index'), 'journal'),
            $model instanceof PrayerRequest => $this->prayerRequestCreated($model),
            $model instanceof PrayerRequestUpdate => $this->prayerRequestUpdateCreated($model),
            $model instanceof Testimony => $this->testimonyCreated($model),
            $model instanceof DevotionalCompletion => ActivityAlerts::notifyOwner($model, 'devotional.completed', 'Devotional completed', 'Your devotional progress was recorded for today.', route('dashboard'), 'check-circle'),
            $model instanceof DevotionalFavorite => ActivityAlerts::notifyOwner($model, 'devotional.saved', 'Devotional saved', 'This devotional was added to your favorites.', route('favorites.index'), 'bookmark'),
            $model instanceof DevotionalPlanCompletion => ActivityAlerts::notifyOwner($model, 'plan.completed', 'Plan day completed', 'Day '.$model->day_number.' was marked complete on your devotional plan.', route('devotional-plans.index'), 'route'),
            $model instanceof MemoryVerseProgress => ActivityAlerts::notifyOwner($model, 'memory.saved', 'Memory verse added', $model->reference.' is ready for practice.', route('memory-verses.index'), 'award'),
            $model instanceof BibleChapterCompletion => ActivityAlerts::notifyOwner($model, 'bible.chapter_completed', 'Bible chapter completed', $this->chapterLabel($model).' was marked complete.', route('daily.index'), 'book-open'),
            $model instanceof UserBibleReadingHistory => ActivityAlerts::notifyOwner($model, 'bible.reading', 'Bible reading recorded', $this->chapterLabel($model).' was added to your reading rhythm.', route('bible'), 'book-open'),
            $model instanceof UserBibleVerseEngagement => ActivityAlerts::notifyOwner($model, 'bible.verse_saved', 'Bible note updated', 'Your Bible verse note, bookmark, or highlight was saved.', route('bible.notes'), 'bookmark'),
            $model instanceof CommunityGroupMembership => ActivityAlerts::notifyOwner($model, 'group.joined', 'Group joined', 'You joined '.$model->group?->name.'.', $this->groupUrl($model), 'users'),
            $model instanceof CommunityGroupPrayer => $this->communityPrayerCreated($model),
            $model instanceof CommunityGroupReadingLog => ActivityAlerts::notifyOwner($model, 'group.reading', 'Group reading recorded', $this->chapterLabel($model).' was recorded for your group.', $this->groupUrl($model), 'book-open'),
            $model instanceof PrayerRoomPrayer => ActivityAlerts::notifyOwner($model, 'prayer_room.prayed', 'Prayer recorded', 'Your prayer was recorded in the prayer room.', route('prayer-rooms.index'), 'heart'),
            $model instanceof UserResourceBookmark => ActivityAlerts::notifyOwner($model, 'resource.saved', 'Resource saved', 'This resource was added to your saved library.', route('offline.library'), 'bookmark'),
            $model instanceof UserResourceProgress => ActivityAlerts::notifyOwner($model, 'resource.progress', 'Resource progress updated', 'Your resource progress was saved.', route('resources.index'), 'check-circle'),
            $model instanceof DailyScripture => ActivityAlerts::notifyAdmins('daily_scripture.synced', 'Daily scripture synced', $model->reference.' was stored for '.$model->verse_date?->format('M j, Y').'.', route('admin.daily-scriptures'), 'book-open'),
            default => null,
        };
    }

    public function updated(Model $model): void
    {
        match (true) {
            $model instanceof DailyRhythmCheckIn => $this->dailyRhythmUpdated($model),
            $model instanceof MemoryVerseProgress => $this->memoryProgressUpdated($model),
            $model instanceof PrayerRequest => $this->prayerRequestUpdated($model),
            $model instanceof CommunityGroupPrayer => $this->communityPrayerUpdated($model),
            $model instanceof Testimony => $this->testimonyUpdated($model),
            $model instanceof UserResourceProgress => $this->resourceProgressUpdated($model),
            default => null,
        };
    }

    private function prayerRequestCreated(PrayerRequest $request): void
    {
        ActivityAlerts::notifyOwner($request, 'prayer.created', 'Prayer request submitted', 'Your prayer request is now part of your prayer rhythm.', route('prayer-requests.wall'), 'heart');
        ActivityAlerts::notifyAdmins('prayer.admin.new', 'New prayer request', ($request->title ?: 'Prayer request').' was submitted.', route('admin.prayer-requests'), 'heart', ['model_id' => $request->id]);
    }

    private function prayerRequestUpdated(PrayerRequest $request): void
    {
        if ($request->wasChanged('is_answered') && $request->is_answered) {
            ActivityAlerts::notifyOwner($request, 'prayer.answered', 'Prayer marked answered', 'Your prayer request was marked answered.', route('prayer-requests.wall'), 'sparkles');
        }
    }

    private function prayerRequestUpdateCreated(PrayerRequestUpdate $update): void
    {
        if ($update->is_answered_update) {
            ActivityAlerts::notifyOwner($update->request, 'prayer.update_answered', 'Answered prayer update added', 'Your answered-prayer update was saved.', route('prayer-requests.wall'), 'sparkles');
        }
    }

    private function testimonyCreated(Testimony $testimony): void
    {
        ActivityAlerts::notifyOwner($testimony, 'testimony.created', 'Testimony submitted', 'Your testimony was received for review.', route('testimonies.index'), 'message-circle');
        ActivityAlerts::notifyAdmins('testimony.admin.new', 'New testimony submitted', ($testimony->title ?: 'A testimony').' is waiting for moderation.', route('admin.moderation'), 'message-circle', ['model_id' => $testimony->id]);
    }

    private function testimonyUpdated(Testimony $testimony): void
    {
        if ($testimony->wasChanged('moderation_status')) {
            $approved = $testimony->moderation_status === Testimony::STATUS_APPROVED;
            $rejected = $testimony->moderation_status === Testimony::STATUS_REJECTED;

            if ($approved || $rejected) {
                ActivityAlerts::notifyOwner(
                    $testimony,
                    $approved ? 'testimony.approved' : 'testimony.rejected',
                    $approved ? 'Testimony approved' : 'Testimony needs revision',
                    $approved ? 'Your testimony is now visible on MannaRise.' : 'Your testimony was reviewed by moderation.',
                    route('testimonies.index'),
                    $approved ? 'check-circle' : 'message-circle',
                );
            }
        }
    }

    private function dailyRhythmUpdated(DailyRhythmCheckIn $checkIn): void
    {
        foreach ([
            'verse_completed_at' => ['daily.verse_completed', 'Daily verse completed', 'Your daily verse was marked complete.', 'star'],
            'affirmation_completed_at' => ['daily.affirmation_completed', 'Daily affirmation completed', 'Your affirmation was marked complete.', 'sparkles'],
            'challenge_completed_at' => ['daily.challenge_completed', 'Bible challenge completed', 'Your daily Bible challenge was marked complete.', 'book-open'],
        ] as $field => [$type, $title, $message, $icon]) {
            if ($checkIn->wasChanged($field) && $checkIn->{$field}) {
                ActivityAlerts::notifyOwner($checkIn, $type, $title, $message, route('daily.index'), $icon);
            }
        }
    }

    private function memoryProgressUpdated(MemoryVerseProgress $progress): void
    {
        if ($progress->wasChanged('completed_at') && $progress->completed_at) {
            ActivityAlerts::notifyOwner($progress, 'memory.completed', 'Memory verse completed', $progress->reference.' was marked memorized.', route('memory-verses.index'), 'award');
        }
    }

    private function communityPrayerCreated(CommunityGroupPrayer $prayer): void
    {
        ActivityAlerts::notifyOwner($prayer, 'group.prayer_created', 'Group prayer posted', 'Your group prayer was shared with '.$prayer->group?->name.'.', $this->groupUrl($prayer), 'heart');
    }

    private function communityPrayerUpdated(CommunityGroupPrayer $prayer): void
    {
        if ($prayer->wasChanged('is_answered') && $prayer->is_answered) {
            ActivityAlerts::notifyOwner($prayer, 'group.prayer_answered', 'Group prayer answered', 'Your group prayer was marked answered.', $this->groupUrl($prayer), 'sparkles');
        }
    }

    private function resourceProgressUpdated(UserResourceProgress $progress): void
    {
        if ($progress->wasChanged('completed_at') && $progress->completed_at) {
            ActivityAlerts::notifyOwner($progress, 'resource.completed', 'Resource completed', 'You completed a saved resource.', route('resources.index'), 'check-circle');
        }
    }

    private function chapterLabel(Model $model): string
    {
        $book = method_exists($model, 'book') ? $model->book?->name : null;

        return trim(($book ?: 'Bible').' '.($model->chapter ?: 'reading'));
    }

    private function groupUrl(Model $model): string
    {
        $group = method_exists($model, 'group') ? $model->group : null;

        return $group ? route('community-groups.show', $group->slug) : route('community-groups.index');
    }
}
