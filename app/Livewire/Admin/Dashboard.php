<?php

namespace App\Livewire\Admin;

use App\Models\BibleBook;
use App\Models\BibleVerse;
use App\Models\Devotional;
use App\Models\DevotionalCategory;
use App\Models\DevotionalCompletion;
use App\Models\DevotionalFavorite;
use App\Models\JournalEntry;
use App\Models\PlatformSetting;
use App\Models\PrayerRequest;
use App\Models\SpiritualBook;
use App\Models\SpiritualBookChapter;
use App\Models\Testimony;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Dashboard extends Component
{
    public string $quickCategoryName = '';
    public string $quickCategoryDescription = '';
    public string $quickDevotionalCategoryId = '';
    public string $quickDevotionalTitle = '';
    public string $quickBibleReference = '';
    public string $quickDevotionalContent = '';
    public bool $quickPublish = false;

    public function createQuickCategory(): void
    {
        $validated = $this->validate([
            'quickCategoryName' => ['required', 'string', 'max:255', Rule::unique('devotional_categories', 'name')],
            'quickCategoryDescription' => ['nullable', 'string', 'max:1000'],
        ]);

        DevotionalCategory::create([
            'name' => $validated['quickCategoryName'],
            'slug' => $this->uniqueCategorySlug($validated['quickCategoryName']),
            'description' => $validated['quickCategoryDescription'] ?? null,
            'is_active' => true,
        ]);

        $this->quickCategoryName = '';
        $this->quickCategoryDescription = '';
        $this->resetErrorBag();
        session()->flash('status', 'Category added from the dashboard.');
    }

    public function createQuickDevotional(): void
    {
        $validated = $this->validate([
            'quickDevotionalCategoryId' => ['required', 'exists:devotional_categories,id'],
            'quickDevotionalTitle' => ['required', 'string', 'max:255'],
            'quickBibleReference' => ['nullable', 'string', 'max:255'],
            'quickDevotionalContent' => ['required', 'string', 'min:20'],
            'quickPublish' => ['boolean'],
        ]);

        Devotional::create([
            'devotional_category_id' => $validated['quickDevotionalCategoryId'],
            'user_id' => auth()->id(),
            'title' => $validated['quickDevotionalTitle'],
            'slug' => $this->uniqueDevotionalSlug($validated['quickDevotionalTitle']),
            'bible_reference' => $validated['quickBibleReference'] ?: null,
            'content' => $validated['quickDevotionalContent'],
            'published_at' => $validated['quickPublish'] ? now() : null,
            'is_featured' => false,
            'is_published' => $validated['quickPublish'],
            'reading_time' => PlatformSetting::value('content.default_reading_time') ?: 5,
        ]);

        $this->quickDevotionalCategoryId = '';
        $this->quickDevotionalTitle = '';
        $this->quickBibleReference = '';
        $this->quickDevotionalContent = '';
        $this->quickPublish = false;
        $this->resetErrorBag();
        session()->flash('status', 'Devotional draft added from the dashboard.');
    }

    public function render()
    {
        $publishedDevotionals = Devotional::where('is_published', true)->count();
        $draftDevotionals = Devotional::where('is_published', false)->count();
        $openPrayers = PrayerRequest::where('is_answered', false)->count();
        $answeredPrayers = PrayerRequest::where('is_answered', true)->count();
        $pendingTestimonies = Testimony::where('is_approved', false)->count();

        return view('livewire.admin.dashboard', [
            'metricGroups' => [
                ['label' => 'Users', 'value' => User::count(), 'icon' => 'users', 'classes' => 'border-indigo-200 bg-indigo-50 text-indigo-900'],
                ['label' => 'Devotionals', 'value' => Devotional::count(), 'icon' => 'sparkles', 'classes' => 'border-amber-200 bg-amber-50 text-amber-900'],
                ['label' => 'Published', 'value' => $publishedDevotionals, 'icon' => 'check-circle', 'classes' => 'border-emerald-200 bg-emerald-50 text-emerald-900'],
                ['label' => 'Drafts', 'value' => $draftDevotionals, 'icon' => 'journal', 'classes' => 'border-slate-200 bg-slate-50 text-slate-900'],
                ['label' => 'Open prayers', 'value' => $openPrayers, 'icon' => 'heart', 'classes' => 'border-rose-200 bg-rose-50 text-rose-900'],
                ['label' => 'Pending testimonies', 'value' => $pendingTestimonies, 'icon' => 'message-circle', 'classes' => 'border-fuchsia-200 bg-fuchsia-50 text-fuchsia-900'],
                ['label' => 'Completions', 'value' => DevotionalCompletion::count(), 'icon' => 'star', 'classes' => 'border-lime-200 bg-lime-50 text-lime-900'],
                ['label' => 'Bible verses', 'value' => BibleVerse::count(), 'icon' => 'book-open', 'classes' => 'border-blue-200 bg-blue-50 text-blue-900'],
            ],
            'contentAreas' => $this->contentAreas($publishedDevotionals, $draftDevotionals, $openPrayers, $answeredPrayers, $pendingTestimonies),
            'projectSnapshot' => $this->projectSnapshot(),
            'settingsSnapshot' => [
                ['label' => 'Verse of the day', 'value' => PlatformSetting::value('daily.verse_enabled') ? 'On' : 'Off'],
                ['label' => 'Affirmations', 'value' => PlatformSetting::value('daily.affirmations_enabled') ? 'On' : 'Off'],
                ['label' => 'Bible challenge', 'value' => PlatformSetting::value('daily.bible_challenge_enabled') ? 'On' : 'Off'],
                ['label' => 'Default timezone', 'value' => PlatformSetting::value('notifications.default_timezone')],
            ],
            'categories' => DevotionalCategory::orderBy('name')->get(),
            'recentDevotionals' => Devotional::with('category')->latest()->take(6)->get(),
            'recentPrayerRequests' => PrayerRequest::latest()->take(5)->get(),
            'recentTestimonies' => Testimony::latest()->take(5)->get(),
        ]);
    }

    private function contentAreas(int $publishedDevotionals, int $draftDevotionals, int $openPrayers, int $answeredPrayers, int $pendingTestimonies): array
    {
        return collect([
            $this->area('Devotional input', 'Create, publish, feature, and edit devotionals.', 'sparkles', 'admin.devotionals', "{$publishedDevotionals} published, {$draftDevotionals} drafts"),
            $this->area('Categories', 'Manage devotional topics and public browsing structure.', 'bookmark', 'admin.categories', DevotionalCategory::count().' topics'),
            $this->area('Prayer moderation', 'Review prayer wall visibility and answered requests.', 'heart', 'admin.prayer-requests', "{$openPrayers} open, {$answeredPrayers} answered"),
            $this->area('Testimony moderation', 'Approve reader testimonies before public display.', 'message-circle', 'admin.testimonies', "{$pendingTestimonies} pending"),
            $this->area('Engagement', 'Review completions, favorites, journals, and prayer activity.', 'bar-chart', 'admin.engagement', DevotionalCompletion::count().' completions'),
            $this->area('Settings', 'Configure site copy, daily modules, and moderation defaults.', 'settings', 'admin.settings', PlatformSetting::allWithDefaults()->count().' settings'),
            $this->area('Daily rhythm', 'Preview verse of the day, affirmation, and Bible challenge.', 'star', 'daily.index', 'Public daily page'),
            $this->area('Bible reader', 'Review the Bible reader and scripture search experience.', 'book-open', 'bible', BibleBook::count().' books'),
            $this->area('Spiritual library', 'Review public-domain books and chapters.', 'library', 'library.index', SpiritualBook::count().' books'),
            $this->optionalArea('Audio devotionals', 'Manage audio readings and publishing.', 'headphones', 'admin.audio-devotionals', 'App\\Models\\AudioDevotional'),
            $this->optionalArea('Roles', 'Manage admin roles and permissions.', 'shield', 'admin.roles', 'App\\Models\\Role'),
        ])->filter()->values()->all();
    }

    private function projectSnapshot(): array
    {
        return [
            ['label' => 'Categories', 'value' => DevotionalCategory::count()],
            ['label' => 'Favorites', 'value' => DevotionalFavorite::count()],
            ['label' => 'Journal entries', 'value' => JournalEntry::count()],
            ['label' => 'Prayer requests', 'value' => PrayerRequest::count()],
            ['label' => 'Testimonies', 'value' => Testimony::count()],
            ['label' => 'Bible books', 'value' => BibleBook::count()],
            ['label' => 'Bible verses', 'value' => BibleVerse::count()],
            ['label' => 'Library books', 'value' => SpiritualBook::count()],
            ['label' => 'Library chapters', 'value' => SpiritualBookChapter::count()],
            ['label' => 'Audio devotionals', 'value' => $this->optionalCount('App\\Models\\AudioDevotional')],
            ['label' => 'Reminders', 'value' => $this->optionalCount('App\\Models\\DevotionalReminder')],
            ['label' => 'Roles', 'value' => $this->optionalCount('App\\Models\\Role')],
        ];
    }

    private function area(string $title, string $description, string $icon, string $route, string $meta): ?array
    {
        if (! Route::has($route)) {
            return null;
        }

        return compact('title', 'description', 'icon', 'route', 'meta') + ['url' => route($route)];
    }

    private function optionalArea(string $title, string $description, string $icon, string $route, string $modelClass): ?array
    {
        if (! class_exists($modelClass) || ! Route::has($route)) {
            return null;
        }

        if ($route === 'admin.roles' && (! method_exists(auth()->user(), 'canDo') || ! auth()->user()->canDo('manage-roles'))) {
            return null;
        }

        return $this->area($title, $description, $icon, $route, $this->optionalCount($modelClass).' records');
    }

    private function optionalCount(string $modelClass): int|string
    {
        if (! class_exists($modelClass) || ! is_subclass_of($modelClass, \Illuminate\Database\Eloquent\Model::class)) {
            return 'Not installed';
        }

        return $modelClass::query()->count();
    }

    private function uniqueCategorySlug(string $name): string
    {
        return $this->uniqueSlug($name, DevotionalCategory::query());
    }

    private function uniqueDevotionalSlug(string $title): string
    {
        return $this->uniqueSlug($title, Devotional::query());
    }

    private function uniqueSlug(string $value, Builder $query): string
    {
        $base = Str::slug($value) ?: 'content';
        $slug = $base;
        $suffix = 2;

        while ((clone $query)->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
