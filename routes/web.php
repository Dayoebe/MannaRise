<?php

use App\Livewire\Admin\AudioDevotionals as AdminAudioDevotionals;
use App\Livewire\Admin\Categories as AdminCategories;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Devotionals as AdminDevotionals;
use App\Livewire\Admin\Engagement as AdminEngagement;
use App\Livewire\Admin\PrayerRequests as AdminPrayerRequests;
use App\Livewire\Admin\Testimonies as AdminTestimonies;
use App\Livewire\AudioDevotionals\Index as AudioDevotionalIndex;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Bible\Reader as BibleReader;
use App\Livewire\Dashboard;
use App\Livewire\Devotionals\Index as DevotionalIndex;
use App\Livewire\Devotionals\Show as DevotionalShow;
use App\Livewire\Favorites\Index as FavoritesIndex;
use App\Livewire\Journal\Index as JournalIndex;
use App\Livewire\Pages\Home;
use App\Livewire\PrayerRequests\Submit as SubmitPrayerRequest;
use App\Livewire\PrayerRequests\Wall as PrayerWall;
use App\Livewire\Reminders\Settings as ReminderSettings;
use App\Livewire\SpiritualLibrary\Index as LibraryIndex;
use App\Livewire\SpiritualLibrary\Show as LibraryShow;
use App\Livewire\Testimonies\Index as TestimonyIndex;
use App\Livewire\Testimonies\Submit as SubmitTestimony;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/bible/{book?}/{chapter?}', BibleReader::class)->name('bible');
Route::get('/library', LibraryIndex::class)->name('library.index');
Route::get('/library/{slug}/{chapter?}', LibraryShow::class)->name('library.show');
Route::get('/devotionals', DevotionalIndex::class)->name('devotionals.index');
Route::get('/devotionals/{slug}', DevotionalShow::class)->name('devotionals.show');
Route::get('/audio-devotionals', AudioDevotionalIndex::class)->name('audio-devotionals.index');
Route::get('/prayer-request', SubmitPrayerRequest::class)->name('prayer-requests.submit');
Route::get('/prayer-wall', PrayerWall::class)->name('prayer-requests.wall');
Route::get('/testimonies', TestimonyIndex::class)->name('testimonies.index');
Route::get('/testimony', SubmitTestimony::class)->name('testimonies.submit');

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
    Route::get('/journal', JournalIndex::class)->name('journal.index');
    Route::get('/favorites', FavoritesIndex::class)->name('favorites.index');
    Route::get('/reminders', ReminderSettings::class)->name('reminders.settings');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/categories', AdminCategories::class)->name('categories');
    Route::get('/devotionals', AdminDevotionals::class)->name('devotionals');
    Route::get('/audio-devotionals', AdminAudioDevotionals::class)->name('audio-devotionals');
    Route::get('/prayer-requests', AdminPrayerRequests::class)->name('prayer-requests');
    Route::get('/testimonies', AdminTestimonies::class)->name('testimonies');
    Route::get('/engagement', AdminEngagement::class)->name('engagement');
});
