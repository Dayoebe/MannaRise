<?php

namespace Tests\Feature;

use App\Livewire\Notifications\Center;
use App\Models\DailyRhythmCheckIn;
use App\Models\JournalEntry;
use App\Models\PrayerRequest;
use App\Models\Testimony;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InAppActivityAlertsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_in_app_alert_when_journal_entry_is_created(): void
    {
        $user = User::factory()->create();

        JournalEntry::create([
            'user_id' => $user->id,
            'title' => 'Morning reflection',
            'content' => 'A meaningful journal reflection for today.',
            'entry_date' => today(),
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
        ]);

        $notification = $user->notifications()->firstOrFail();

        $this->assertSame('journal.created', $notification->data['type']);
        $this->assertSame('Journal reflection saved', $notification->data['title']);
    }

    public function test_daily_rhythm_completion_creates_in_app_alert(): void
    {
        $user = User::factory()->create();
        $checkIn = DailyRhythmCheckIn::create([
            'user_id' => $user->id,
            'checked_on' => today(),
        ]);

        $checkIn->update(['verse_completed_at' => now()]);

        $this->assertSame('daily.verse_completed', $user->notifications()->firstOrFail()->data['type']);
    }

    public function test_admin_receives_alert_for_moderation_activity(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        PrayerRequest::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'title' => 'Please pray',
            'body' => 'Please pray for strength and wisdom today.',
            'is_public' => true,
        ]);

        Testimony::create([
            'user_id' => $user->id,
            'title' => 'God helped me',
            'body' => 'This is a testimony submitted for moderation.',
            'moderation_status' => Testimony::STATUS_PENDING,
        ]);

        $types = $admin->notifications()->get()->pluck('data.type');

        $this->assertContains('prayer.admin.new', $types);
        $this->assertContains('testimony.admin.new', $types);
    }

    public function test_notification_center_marks_alerts_as_read(): void
    {
        $user = User::factory()->create();

        JournalEntry::create([
            'user_id' => $user->id,
            'title' => 'Evening reflection',
            'content' => 'A meaningful journal reflection for tonight.',
            'entry_date' => today(),
        ]);

        $notification = $user->unreadNotifications()->firstOrFail();

        Livewire::actingAs($user)
            ->test(Center::class)
            ->assertSee('In-app alerts')
            ->call('markAsRead', $notification->id);

        $this->assertNotNull($notification->fresh()->read_at);
    }
}
