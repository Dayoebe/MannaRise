<?php

namespace Tests\Feature;

use App\Livewire\PrayerRooms\Show as PrayerRoomShow;
use App\Models\Devotional;
use App\Models\DevotionalCategory;
use App\Models\PrayerRequest;
use App\Models\PrayerRoom;
use App\Models\Testimony;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MannaRisePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render(): void
    {
        $category = DevotionalCategory::create([
            'name' => 'Faith',
            'slug' => 'faith',
            'is_active' => true,
        ]);

        Devotional::create([
            'devotional_category_id' => $category->id,
            'title' => 'Faith for Today',
            'slug' => 'faith-for-today',
            'content' => 'A devotional body long enough for validation and display.',
            'published_at' => now(),
            'is_published' => true,
            'is_featured' => true,
            'reading_time' => 4,
        ]);

        Testimony::create([
            'title' => 'A good report',
            'body' => 'This is an approved testimony for public display.',
            'is_approved' => true,
        ]);

        foreach (['/', '/about', '/contact', '/daily', '/bible', '/library', '/devotionals', '/devotionals/faith-for-today', '/plans', '/memory-verses', '/scripture-cards', '/guided-prayer', '/pray-with-me', '/pray-with-me/faith-for-today', '/audio-devotionals', '/resources', '/resources/devotion', '/resources/books', '/resources/videos', '/resources/audio', '/prayer-rooms', '/prayer-rooms/healing', '/prayer-wall', '/prayer-request', '/testimonies', '/testimony', '/login', '/register'] as $path) {
            $this->get($path)->assertOk();
        }

        $this->get('/scripture-cards')
            ->assertOk()
            ->assertSee('Note card')
            ->assertSee('Invite prayer')
            ->assertSee('data-note-body', false)
            ->assertSee('Shareable Scripture and note cards');

        $this->get('/devotionals/faith-for-today')
            ->assertOk()
            ->assertSee('Share this devotion')
            ->assertSee('WhatsApp share')
            ->assertSee('Copy link')
            ->assertSee('Download image')
            ->assertSee('Invite someone to pray with you');

        $this->get('/pray-with-me/faith-for-today')
            ->assertOk()
            ->assertSee('Pray through this devotion together')
            ->assertSee('Start guided prayer')
            ->assertSee('data-prayer-invite-share', false);
    }

    public function test_authenticated_pages_render(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        foreach (['/dashboard', '/growth-path', '/journal', '/favorites', '/reminders', '/groups'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_admin_pages_render(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        foreach (['/admin', '/admin/categories', '/admin/devotionals', '/admin/resource-categories', '/admin/resource-items', '/admin/daily-devotions', '/admin/daily-scriptures', '/admin/featured-content', '/admin/moderation', '/admin/prayer-requests', '/admin/testimonies', '/admin/engagement', '/admin/settings'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_super_admin_has_admin_access(): void
    {
        $superAdmin = User::factory()->create([
            'is_admin' => false,
            'is_super_admin' => true,
        ]);

        $this->actingAs($superAdmin);

        foreach (['/admin', '/admin/categories', '/admin/devotionals', '/admin/daily-scriptures', '/admin/featured-content', '/admin/moderation', '/admin/prayer-requests', '/admin/testimonies', '/admin/engagement', '/admin/audio-devotionals', '/admin/roles', '/admin/settings'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_user_can_join_prayer_room_and_track_prayer_streak(): void
    {
        $user = User::factory()->create();

        PrayerRoom::syncDefaults();
        $room = PrayerRoom::where('slug', 'healing')->firstOrFail();

        $request = PrayerRequest::create([
            'user_id' => $user->id,
            'prayer_room_id' => $room->id,
            'name' => $user->name,
            'email' => $user->email,
            'title' => 'Healing and strength',
            'body' => 'Please pray for healing, strength, peace, and patience through recovery.',
            'is_public' => true,
        ]);

        Livewire::actingAs($user)
            ->test(PrayerRoomShow::class, ['room' => 'healing'])
            ->call('join')
            ->call('pray', $request->id)
            ->call('beginAnsweredUpdate', $request->id)
            ->set('answeredUpdateBody', 'God answered this prayer with strength and peace.')
            ->call('addAnsweredUpdate', $request->id);

        $this->assertDatabaseHas('prayer_room_memberships', [
            'user_id' => $user->id,
            'prayer_room_id' => $room->id,
            'current_streak' => 1,
            'longest_streak' => 1,
            'total_prayers' => 1,
        ]);

        $this->assertDatabaseHas('prayer_request_updates', [
            'prayer_request_id' => $request->id,
            'user_id' => $user->id,
            'is_answered_update' => true,
        ]);

        $this->assertDatabaseHas('prayer_requests', [
            'id' => $request->id,
            'is_answered' => true,
            'prayed_count' => 1,
        ]);
    }

    public function test_pwa_assets_are_configured(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('/manifest.webmanifest', false)
            ->assertSee('apple-mobile-web-app-capable', false)
            ->assertSee('/icons/apple-touch-icon.png', false);

        $manifest = json_decode(file_get_contents(public_path('manifest.webmanifest')), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame('standalone', $manifest['display']);
        $this->assertSame('/', $manifest['scope']);
        $this->assertFileExists(public_path('sw.js'));
        $this->assertFileExists(public_path('offline.html'));
        $this->assertFileExists(public_path('icons/icon-192.png'));
        $this->assertFileExists(public_path('icons/icon-512.png'));
        $this->assertFileExists(public_path('icons/maskable-512.png'));
        $this->assertFileExists(public_path('icons/apple-touch-icon.png'));
        $this->assertFileDoesNotExist(public_path('hot'));
    }
}
