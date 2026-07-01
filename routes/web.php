<?php

use App\Http\Controllers\SeoController;
use App\Livewire\Admin\AudioDevotionals as AdminAudioDevotionals;
use App\Livewire\Admin\Categories as AdminCategories;
use App\Livewire\Admin\DailyDevotions as AdminDailyDevotions;
use App\Livewire\Admin\DailyScriptures as AdminDailyScriptures;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Devotionals as AdminDevotionals;
use App\Livewire\Admin\Engagement as AdminEngagement;
use App\Livewire\Admin\FeaturedContent as AdminFeaturedContent;
use App\Livewire\Admin\ModerationQueue as AdminModerationQueue;
use App\Livewire\Admin\NotificationDeliveries as AdminNotificationDeliveries;
use App\Livewire\Admin\PrayerRequests as AdminPrayerRequests;
use App\Livewire\Admin\ResourceCategories as AdminResourceCategories;
use App\Livewire\Admin\ResourceItems as AdminResourceItems;
use App\Livewire\Admin\Roles as AdminRoles;
use App\Livewire\Admin\Settings as AdminSettings;
use App\Livewire\Admin\Testimonies as AdminTestimonies;
use App\Livewire\Admin\Users as AdminUsers;
use App\Livewire\AudioDevotionals\Index as AudioDevotionalIndex;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Bible\Notes as BibleNotes;
use App\Livewire\Bible\Reader as BibleReader;
use App\Livewire\CommunityGroups\Index as CommunityGroupIndex;
use App\Livewire\CommunityGroups\Show as CommunityGroupShow;
use App\Livewire\Daily\Index as DailyIndex;
use App\Livewire\Dashboard;
use App\Livewire\DevotionalPlans\Index as DevotionalPlanIndex;
use App\Livewire\DevotionalPlans\Show as DevotionalPlanShow;
use App\Livewire\Devotionals\Index as DevotionalIndex;
use App\Livewire\Devotionals\Show as DevotionalShow;
use App\Livewire\Favorites\Index as FavoritesIndex;
use App\Livewire\GrowthPath\Index as GrowthPathIndex;
use App\Livewire\Journal\Index as JournalIndex;
use App\Livewire\MemoryVerses\Index as MemoryVerseIndex;
use App\Livewire\OfflineLibrary;
use App\Livewire\Onboarding\Index as OnboardingIndex;
use App\Livewire\Pages\About;
use App\Livewire\Pages\Contact;
use App\Livewire\Pages\Home;
use App\Livewire\PrayerInvites\Show as PrayerInviteShow;
use App\Livewire\PrayerRequests\Submit as SubmitPrayerRequest;
use App\Livewire\PrayerRequests\Wall as PrayerWall;
use App\Livewire\PrayerRooms\Index as PrayerRoomIndex;
use App\Livewire\PrayerRooms\Show as PrayerRoomShow;
use App\Livewire\PrayerSessions\Index as PrayerSessionIndex;
use App\Livewire\Reminders\Settings as ReminderSettings;
use App\Livewire\ResourceHub\Audio as ResourceAudio;
use App\Livewire\ResourceHub\Books as ResourceBooks;
use App\Livewire\ResourceHub\DailyDevotion as ResourceDailyDevotion;
use App\Livewire\ResourceHub\Index as ResourceHubIndex;
use App\Livewire\ResourceHub\ShowResource;
use App\Livewire\ResourceHub\Videos as ResourceVideos;
use App\Livewire\ScriptureCards\Index as ScriptureCardIndex;
use App\Livewire\SpiritualLibrary\Index as LibraryIndex;
use App\Livewire\SpiritualLibrary\Show as LibraryShow;
use App\Livewire\Testimonies\Index as TestimonyIndex;
use App\Livewire\Testimonies\Submit as SubmitTestimony;
use App\Models\CommunityGroupInvite;
use App\Models\CommunityGroupMembership;
use App\Models\DevotionalReminder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/sitemap-pages.xml', [SeoController::class, 'sitemapPages'])->name('seo.sitemap.pages');
Route::get('/sitemap-devotionals.xml', [SeoController::class, 'sitemapDevotionals'])->name('seo.sitemap.devotionals');
Route::get('/sitemap-resources.xml', [SeoController::class, 'sitemapResources'])->name('seo.sitemap.resources');
Route::get('/sitemap-library.xml', [SeoController::class, 'sitemapLibrary'])->name('seo.sitemap.library');
Route::get('/sitemap-images.xml', [SeoController::class, 'sitemapImages'])->name('seo.sitemap.images');
Route::get('/feed.xml', [SeoController::class, 'feed'])->name('seo.feed');
Route::get('/feed.atom', [SeoController::class, 'atom'])->name('seo.feed.atom');
Route::get('/llms.txt', [SeoController::class, 'llms'])->name('seo.llms');
Route::get('/llms-full.txt', [SeoController::class, 'llmsFull'])->name('seo.llms-full');
Route::get('/ai.txt', [SeoController::class, 'ai'])->name('seo.ai');

Route::get('/', Home::class)->name('home');
Route::get('/about', About::class)->name('about');
Route::get('/contact', Contact::class)->name('contact');
Route::get('/daily', DailyIndex::class)->name('daily.index');
Route::get('/bible/{book?}/{chapter?}', BibleReader::class)->name('bible');
Route::get('/library', LibraryIndex::class)->name('library.index');
Route::get('/library/{slug}/{chapter?}', LibraryShow::class)->name('library.show');
Route::get('/devotionals', DevotionalIndex::class)->name('devotionals.index');
Route::get('/devotionals/{slug}', DevotionalShow::class)->name('devotionals.show');
Route::get('/plans', DevotionalPlanIndex::class)->name('devotional-plans.index');
Route::get('/plans/{plan}', DevotionalPlanShow::class)->name('devotional-plans.show');
Route::get('/memory-verses', MemoryVerseIndex::class)->name('memory-verses.index');
Route::get('/scripture-cards', ScriptureCardIndex::class)->name('scripture-cards.index');
Route::get('/guided-prayer', PrayerSessionIndex::class)->name('prayer-sessions.index');
Route::get('/audio-devotionals', AudioDevotionalIndex::class)->name('audio-devotionals.index');
Route::get('/resources', ResourceHubIndex::class)->name('resources.index');
Route::get('/resources/devotion', ResourceDailyDevotion::class)->name('resources.devotion');
Route::get('/resources/devotion/{slug}', ResourceDailyDevotion::class)->name('resources.devotion.show');
Route::get('/resources/books', ResourceBooks::class)->name('resources.books');
Route::get('/resources/videos', ResourceVideos::class)->name('resources.videos');
Route::get('/resources/audio', ResourceAudio::class)->name('resources.audio');
Route::get('/resources/{slug}', ShowResource::class)->name('resources.show');
Route::get('/prayer-rooms', PrayerRoomIndex::class)->name('prayer-rooms.index');
Route::get('/prayer-rooms/{room}', PrayerRoomShow::class)->name('prayer-rooms.show');
Route::get('/pray-with-me/{devotionalSlug?}', PrayerInviteShow::class)->name('prayer-invites.show');
Route::get('/prayer-request', SubmitPrayerRequest::class)->name('prayer-requests.submit');
Route::get('/prayer-wall', PrayerWall::class)->name('prayer-requests.wall');
Route::get('/testimonies', TestimonyIndex::class)->name('testimonies.index');
Route::get('/testimony', SubmitTestimony::class)->name('testimonies.submit');

Route::get('/mail/notifications/opt-out/{user}', function (Request $request, User $user) {
    abort_unless($request->hasValidSignature(), 403);

    $reminder = DevotionalReminder::firstOrCreate(
        ['user_id' => $user->id],
        [
            'title' => 'Daily devotional reminder',
            'remind_at' => '06:00:00',
            'timezone' => config('app.timezone'),
            'days' => [
                'weekdays' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                'types' => ['devotional', 'bible', 'prayer', 'missed', 'digest'],
            ],
            'push_enabled' => true,
            'is_active' => true,
        ],
    );

    $reminder->forceFill(['email_enabled' => false])->save();

    return redirect()->route('reminders.settings')->with('status', 'Email notifications have been turned off. In-app reminders can still stay active.');
})->middleware('signed')->name('mail.notifications.opt-out');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/onboarding', OnboardingIndex::class)->name('onboarding');
    Route::get('/growth-path', GrowthPathIndex::class)->name('growth-path.index');
    Route::get('/journal', JournalIndex::class)->name('journal.index');
    Route::get('/bible-notes', BibleNotes::class)->name('bible.notes');
    Route::get('/favorites', FavoritesIndex::class)->name('favorites.index');
    Route::get('/reminders', ReminderSettings::class)->name('reminders.settings');
    Route::get('/offline-library', OfflineLibrary::class)->name('offline.library');
    Route::get('/groups', CommunityGroupIndex::class)->name('community-groups.index');
    Route::get('/groups/invite/{token}', function (string $token) {
        $invite = CommunityGroupInvite::with('group')
            ->where('token', $token)
            ->firstOrFail();

        abort_unless($invite->isUsable(), 404);

        $membership = CommunityGroupMembership::firstOrCreate(
            [
                'community_group_id' => $invite->community_group_id,
                'user_id' => auth()->id(),
            ],
            [
                'role' => 'member',
                'joined_at' => now(),
            ],
        );

        if ($membership->wasRecentlyCreated) {
            $invite->increment('uses_count');
        }

        session()->flash('status', "You joined {$invite->group->name}.");

        return redirect()->route('community-groups.show', $invite->group->slug);
    })->name('community-groups.invite');
    Route::get('/groups/{group}', CommunityGroupShow::class)->name('community-groups.show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/categories', AdminCategories::class)->middleware('permission:manage-categories')->name('categories');
    Route::get('/devotionals', AdminDevotionals::class)->middleware('permission:manage-devotionals')->name('devotionals');
    Route::get('/resource-categories', AdminResourceCategories::class)->middleware('permission:manage-devotionals')->name('resource-categories');
    Route::get('/resource-items', AdminResourceItems::class)->middleware('permission:manage-devotionals')->name('resource-items');
    Route::get('/daily-devotions', AdminDailyDevotions::class)->middleware('permission:manage-devotionals')->name('daily-devotions');
    Route::get('/daily-scriptures', AdminDailyScriptures::class)->middleware('permission:manage-devotionals')->name('daily-scriptures');
    Route::get('/featured-content', AdminFeaturedContent::class)->middleware('permission:manage-devotionals')->name('featured-content');
    Route::get('/moderation', AdminModerationQueue::class)->middleware('permission:manage-testimonies')->name('moderation');
    Route::get('/prayer-requests', AdminPrayerRequests::class)->middleware('permission:manage-prayer-requests')->name('prayer-requests');
    Route::get('/testimonies', AdminTestimonies::class)->middleware('permission:manage-testimonies')->name('testimonies');
    Route::get('/engagement', AdminEngagement::class)->middleware('permission:view-engagement')->name('engagement');
    Route::get('/notifications', AdminNotificationDeliveries::class)->middleware('permission:manage-notifications')->name('notifications');
    Route::get('/audio-devotionals', AdminAudioDevotionals::class)->middleware('permission:manage-audio-devotionals')->name('audio-devotionals');
    Route::get('/users', AdminUsers::class)->middleware('permission:manage-users')->name('users');
    Route::get('/roles', AdminRoles::class)->middleware('permission:manage-roles')->name('roles');
    Route::get('/settings', AdminSettings::class)->middleware('permission:manage-dashboard')->name('settings');
});
