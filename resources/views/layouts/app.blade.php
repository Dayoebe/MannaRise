@php
    $seo = \App\Support\Seo::meta($seo ?? []);
    $activeLocale = \App\Support\LanguagePreference::current();
    $activeLanguage = \App\Support\LanguagePages::language($activeLocale);
    $navCopy = \App\Support\LanguagePreference::navCopy($activeLocale);
    $languageOptions = \App\Support\LanguagePreference::options();
    $homeUrl = \App\Support\LanguagePreference::homeUrl($activeLocale);
    $dailyUrl = \App\Support\LanguagePreference::dailyUrl($activeLocale);
    $bibleUrl = \App\Support\LanguagePreference::bibleUrl($activeLocale);
@endphp

<!DOCTYPE html>
<html lang="{{ $activeLanguage['html_locale'] ?? $seo['language'] }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <meta name="theme-color" content="{{ config('seo.theme_color') }}">
        <meta name="color-scheme" content="light">
        <meta name="application-name" content="{{ $seo['site_name'] }}">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="{{ $seo['site_name'] }}">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="robots" content="{{ $seo['robots'] }}">
        <meta name="description" content="{{ $seo['description'] }}">
        <meta name="author" content="{{ $seo['site_name'] }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="growth-analytics-endpoint" content="{{ route('analytics.events') }}">

        <title>{{ $seo['title'] }}</title>
        <link rel="canonical" href="{{ $seo['canonical'] }}">
        @foreach ($seo['alternates'] as $hreflang => $href)
            <link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $href }}">
        @endforeach

        <meta property="og:locale" content="{{ $seo['og_locale'] }}">
        <meta property="og:site_name" content="{{ $seo['site_name'] }}">
        <meta property="og:type" content="{{ $seo['type'] }}">
        <meta property="og:title" content="{{ $seo['title'] }}">
        <meta property="og:description" content="{{ $seo['description'] }}">
        <meta property="og:url" content="{{ $seo['canonical'] }}">
        <meta property="og:image" content="{{ $seo['image'] }}">
        <meta property="og:image:alt" content="{{ $seo['image_alt'] }}">
        <meta property="og:image:width" content="{{ $seo['image_width'] }}">
        <meta property="og:image:height" content="{{ $seo['image_height'] }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $seo['title'] }}">
        <meta name="twitter:description" content="{{ $seo['description'] }}">
        <meta name="twitter:image" content="{{ $seo['image'] }}">
        <meta name="twitter:image:alt" content="{{ $seo['image_alt'] }}">
        @if ($seo['twitter_site'])
            <meta name="twitter:site" content="{{ $seo['twitter_site'] }}">
        @endif

        <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ route('seo.sitemap') }}">
        <link rel="alternate" type="application/rss+xml" title="{{ $seo['site_name'] }} RSS Feed" href="{{ route('seo.feed') }}">
        <link rel="alternate" type="application/atom+xml" title="{{ $seo['site_name'] }} Atom Feed" href="{{ route('seo.feed.atom') }}">
        <link rel="manifest" href="/manifest.webmanifest">
        <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
        <link rel="mask-icon" href="/icons/icon-512.svg" color="{{ config('seo.theme_color') }}">

        @foreach ($seo['schema'] as $schema)
            <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
        @endforeach

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="app-shell flex min-h-screen flex-col bg-mist-50 text-slate-950 antialiased">
        @php
            $user = auth()->user();
            $usesAccountSidebar = (bool) $user;
            $accountSidebarMenu = $usesAccountSidebar ? \App\Support\DashboardMenu::forUser($user) : [];
        @endphp

        <header class="app-topbar sticky top-0 z-40 border-b border-slate-200 bg-mist-50/95 backdrop-blur">
            <div class="color-strip hidden rounded-none lg:flex">
                <span class="bg-red-500"></span>
                <span class="bg-orange-500"></span>
                <span class="bg-amber-400"></span>
                <span class="bg-yellow-400"></span>
                <span class="bg-lime-500"></span>
                <span class="bg-green-500"></span>
                <span class="bg-emerald-500"></span>
                <span class="bg-teal-500"></span>
                <span class="bg-cyan-500"></span>
                <span class="bg-sky-500"></span>
                <span class="bg-blue-500"></span>
                <span class="bg-indigo-500"></span>
                <span class="bg-violet-500"></span>
                <span class="bg-purple-500"></span>
                <span class="bg-fuchsia-500"></span>
                <span class="bg-pink-500"></span>
                <span class="bg-rose-500"></span>
            </div>

            <div class="app-topbar-inner mx-auto flex max-w-7xl flex-col gap-3 px-3 py-3 sm:px-5 lg:px-8">
                <div class="flex items-center justify-between gap-3">
                    <a href="{{ $homeUrl }}" class="app-brand group inline-flex min-h-12 items-center gap-3 rounded-2xl border border-emerald-200 bg-white px-3 py-2 shadow-sm transition hover:border-emerald-300">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-700 text-white">
                            <x-ui.icon name="sparkles" class="h-5 w-5" />
                        </span>
                        <span>
                            <span class="block font-display text-lg font-bold tracking-normal text-emerald-900">MannaRise</span>
                            <span class="block text-xs font-semibold text-slate-500">{{ $navCopy['grow_daily'] }}</span>
                        </span>
                    </a>

                    <div class="flex shrink-0 items-center gap-2">
                        <details class="language-switcher">
                            <summary aria-label="{{ $navCopy['choose_language'] }}">
                                <x-ui.icon name="globe" class="h-4 w-4" />
                                <span class="hidden max-w-28 truncate sm:inline">{{ $activeLanguage['native_name'] }}</span>
                                <span class="sm:hidden">{{ strtoupper($activeLocale) }}</span>
                                <x-ui.icon name="chevron-right" class="h-3.5 w-3.5 language-switcher-icon" />
                            </summary>

                            <div class="language-menu-panel">
                                <p>{{ $navCopy['choose_language'] }}</p>

                                <div class="mt-2 grid gap-1">
                                    @foreach ($languageOptions as $option)
                                        <a href="{{ $option['switch_url'] }}" class="language-menu-link {{ $option['current'] ? 'language-menu-link-active' : '' }}">
                                            <span>{{ strtoupper($option['code']) }}</span>
                                            <strong>{{ $option['native_name'] }}</strong>
                                            <small>{{ $option['name'] }}</small>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </details>

                        @auth
                            <livewire:notifications.center />
                            @unless ($usesAccountSidebar)
                                <a href="{{ route('dashboard') }}" class="btn-secondary px-3" title="Dashboard">
                                    <x-ui.icon name="layout-dashboard" class="h-4 w-4" />
                                    <span class="hidden sm:inline">{{ $navCopy['dashboard'] }}</span>
                                </a>
                            @endunless
                            @if (auth()->user()->hasAdminAccess())
                                <a href="{{ route('admin.dashboard') }}" class="btn-secondary px-3" title="Admin">
                                    <x-ui.icon name="shield" class="h-4 w-4" />
                                    <span class="hidden sm:inline">{{ auth()->user()->is_super_admin ? $navCopy['super_admin'] : $navCopy['admin'] }}</span>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-secondary px-3" title="{{ $navCopy['log_out'] }}">
                                    <x-ui.icon name="log-out" class="h-4 w-4" />
                                    <span class="hidden sm:inline">{{ $navCopy['log_out'] }}</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-secondary px-3">
                                <x-ui.icon name="log-in" class="h-4 w-4" />
                                <span class="hidden sm:inline">{{ $navCopy['log_in'] }}</span>
                            </a>
                            <a href="{{ route('register') }}" class="btn-primary px-3">
                                <x-ui.icon name="sparkles" class="h-4 w-4" />
                                <span class="hidden sm:inline">{{ $navCopy['join'] }}</span>
                            </a>
                        @endauth
                    </div>
                </div>

                @php
                    $primaryNavLinks = [
                        ['label' => $navCopy['home'], 'url' => $homeUrl, 'icon' => 'sparkles', 'active' => ['home', 'localized.home']],
                        ['label' => $navCopy['daily'], 'url' => $dailyUrl, 'icon' => 'star', 'active' => ['daily.*']],
                        ['label' => $navCopy['bible'], 'url' => $bibleUrl, 'icon' => 'book-open', 'active' => ['bible']],
                        ['label' => $navCopy['devotionals'], 'route' => 'devotionals.index', 'icon' => 'sparkles', 'active' => ['devotionals.*']],
                        ['label' => $navCopy['prayer'], 'route' => 'prayer-sessions.index', 'icon' => 'heart', 'active' => ['prayer-sessions.*', 'prayer-invites.*', 'prayer-rooms.*', 'prayer-requests.*']],
                    ];

                    if ($user) {
                        $primaryNavLinks[] = ['label' => $navCopy['journal'], 'route' => 'journal.index', 'icon' => 'journal', 'active' => ['journal.*']];
                    }

                    $exploreLinks = [
                        ['label' => $navCopy['resources'], 'route' => 'resources.index', 'icon' => 'library', 'active' => ['resources.index', 'resources.show']],
                        ['label' => 'Plans', 'route' => 'devotional-plans.index', 'icon' => 'route', 'active' => ['devotional-plans.*']],
                        ['label' => 'Library', 'route' => 'library.index', 'icon' => 'library', 'active' => ['library.*']],
                        ['label' => 'Hub Books', 'route' => 'resources.books', 'icon' => 'library', 'active' => ['resources.books']],
                        ['label' => 'Hub Audio', 'route' => 'resources.audio', 'icon' => 'headphones', 'active' => ['resources.audio']],
                        ['label' => 'Videos', 'route' => 'resources.videos', 'icon' => 'play', 'active' => ['resources.videos']],
                    ];

                    $moreLinks = [
                        ['label' => 'Memory', 'route' => 'memory-verses.index', 'icon' => 'bookmark', 'active' => ['memory-verses.*']],
                        ['label' => 'Cards', 'route' => 'scripture-cards.index', 'icon' => 'book-open', 'active' => ['scripture-cards.*']],
                        ['label' => 'Audio', 'route' => 'audio-devotionals.index', 'icon' => 'headphones', 'active' => ['audio-devotionals.*']],
                        ['label' => 'Testimonies', 'route' => 'testimonies.index', 'icon' => 'message-circle', 'active' => ['testimonies.*']],
                    ];

                    if ($user) {
                        $moreLinks[] = ['label' => 'Groups', 'route' => 'community-groups.index', 'icon' => 'users', 'active' => ['community-groups.*']];
                    }

                    $accountLinks = [];

                    if ($user) {
                        $accountLinks = [
                            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'layout-dashboard', 'active' => ['dashboard']],
                            ['label' => 'Bible Notes', 'route' => 'bible.notes', 'icon' => 'bookmark', 'active' => ['bible.notes']],
                            ['label' => 'Favorites', 'route' => 'favorites.index', 'icon' => 'bookmark', 'active' => ['favorites.*']],
                            ['label' => 'Path', 'route' => 'growth-path.index', 'icon' => 'route', 'active' => ['growth-path.*']],
                            ['label' => 'Reminders', 'route' => 'reminders.settings', 'icon' => 'star', 'active' => ['reminders.*']],
                            ['label' => 'Offline', 'route' => 'offline.library', 'icon' => 'download', 'active' => ['offline.*']],
                        ];
                    }

                    $adminLinks = [];

                    if ($user?->hasAdminAccess()) {
                        $adminLinks = [
                            ['label' => 'Admin Home', 'route' => 'admin.dashboard', 'icon' => 'shield', 'active' => ['admin.dashboard']],
                            ['label' => 'Content', 'route' => 'admin.devotionals', 'icon' => 'sparkles', 'active' => ['admin.categories', 'admin.devotionals', 'admin.featured-content']],
                            ['label' => 'Resource Admin', 'route' => 'admin.resource-items', 'icon' => 'library', 'active' => ['admin.resource-categories', 'admin.resource-items', 'admin.daily-devotions', 'admin.daily-scriptures']],
                            ['label' => 'Moderation', 'route' => 'admin.moderation', 'icon' => 'message-circle', 'active' => ['admin.moderation', 'admin.prayer-requests', 'admin.testimonies']],
                            ['label' => 'Engagement', 'route' => 'admin.engagement', 'icon' => 'bar-chart', 'active' => ['admin.engagement']],
                            ['label' => 'Settings', 'route' => 'admin.settings', 'icon' => 'settings', 'active' => ['admin.settings']],
                        ];

                        if ($user->canDo('manage-notifications')) {
                            $adminLinks[] = ['label' => 'Mail Center', 'route' => 'admin.notifications', 'icon' => 'mail', 'active' => ['admin.notifications']];
                        }

                        if ($user->canDo('manage-users')) {
                            $adminLinks[] = ['label' => 'Users', 'route' => 'admin.users', 'icon' => 'users', 'active' => ['admin.users']];
                        }

                        if ($user->canDo('manage-audio-devotionals')) {
                            $adminLinks[] = ['label' => 'Audio Admin', 'route' => 'admin.audio-devotionals', 'icon' => 'headphones', 'active' => ['admin.audio-devotionals']];
                        }

                        if ($user->canDo('manage-roles')) {
                            $adminLinks[] = ['label' => 'Roles', 'route' => 'admin.roles', 'icon' => 'shield', 'active' => ['admin.roles']];
                        }
                    }

                    $desktopNavMenus = array_filter([
                        ['label' => $navCopy['explore'], 'icon' => 'library', 'links' => $exploreLinks],
                        ['label' => $navCopy['more'], 'icon' => 'more-horizontal', 'links' => $moreLinks],
                        ['label' => $navCopy['my_space'], 'icon' => 'layout-dashboard', 'links' => $accountLinks],
                        ['label' => $navCopy['admin'], 'icon' => 'shield', 'links' => $adminLinks],
                    ], fn (array $group) => count($group['links']) > 0);

                    $mobileMoreGroups = [
                        ['label' => $navCopy['explore'], 'links' => $exploreLinks],
                        ['label' => $navCopy['more'], 'links' => $moreLinks],
                        ['label' => $navCopy['prayer'], 'links' => [
                            ['label' => $navCopy['guided_prayer'], 'route' => 'prayer-sessions.index', 'icon' => 'heart', 'active' => ['prayer-sessions.*', 'prayer-invites.*']],
                            ['label' => $navCopy['prayer_rooms'], 'route' => 'prayer-rooms.index', 'icon' => 'users', 'active' => ['prayer-rooms.*']],
                            ['label' => $navCopy['prayer_wall'], 'route' => 'prayer-requests.wall', 'icon' => 'heart', 'active' => ['prayer-requests.wall']],
                            ['label' => $navCopy['request_prayer'], 'route' => 'prayer-requests.submit', 'icon' => 'send', 'active' => ['prayer-requests.submit']],
                        ]],
                    ];

                    if ($user) {
                        $mobileMoreGroups[] = ['label' => $navCopy['my_space'], 'links' => $accountLinks];
                    }

                    if ($user?->hasAdminAccess()) {
                        $mobileMoreGroups[] = ['label' => $navCopy['admin'], 'links' => $adminLinks];
                    }

                    $mobileMoreActiveRoutes = collect($mobileMoreGroups)
                        ->flatMap(fn (array $group) => collect($group['links'])->flatMap(fn (array $link) => $link['active']))
                        ->all();
                @endphp

                <nav class="desktop-navbar hidden items-center gap-2 text-sm lg:flex" aria-label="Primary navigation">
                    <div class="desktop-nav-primary">
                        @foreach ($primaryNavLinks as $link)
                            <a href="{{ $link['url'] ?? route($link['route']) }}" class="nav-pill {{ request()->routeIs(...$link['active']) ? 'nav-pill-active' : '' }}">
                                <x-ui.icon :name="$link['icon']" class="h-4 w-4" /> {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>

                    <div class="desktop-nav-menus">
                        @foreach ($desktopNavMenus as $group)
                            @php
                                $isMenuActive = collect($group['links'])->contains(fn (array $link): bool => request()->routeIs(...$link['active']));
                            @endphp

                            <details class="desktop-nav-menu">
                                <summary class="desktop-nav-summary {{ $isMenuActive ? 'desktop-nav-summary-active' : '' }}">
                                    <x-ui.icon :name="$group['icon']" class="h-4 w-4" />
                                    {{ $group['label'] }}
                                    <x-ui.icon name="chevron-right" class="desktop-nav-summary-icon h-3.5 w-3.5" />
                                </summary>

                                <div class="desktop-nav-panel">
                                    @foreach ($group['links'] as $link)
                                        <a href="{{ $link['url'] ?? route($link['route']) }}" class="desktop-nav-link {{ request()->routeIs(...$link['active']) ? 'desktop-nav-link-active' : '' }}">
                                            <x-ui.icon :name="$link['icon']" class="h-4 w-4" />
                                            <span>{{ $link['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </details>
                        @endforeach
                    </div>
                </nav>
            </div>
        </header>

        <div class="app-body mx-auto grid w-full max-w-7xl flex-1 gap-6 px-3 pt-5 pb-28 sm:px-5 sm:pt-8 lg:px-8 lg:pb-8 {{ $usesAccountSidebar ? 'lg:grid-cols-[17rem_minmax(0,1fr)] lg:items-start' : '' }}">
            @auth
                <aside class="dashboard-sidebar" aria-label="Account menu">
                    <div class="dashboard-sidebar-card">
                        <div class="color-strip rounded-none">
                            <span class="bg-emerald-500"></span>
                            <span class="bg-teal-500"></span>
                            <span class="bg-cyan-500"></span>
                            <span class="bg-sky-500"></span>
                            <span class="bg-violet-500"></span>
                        </div>

                        <div class="p-4">
                            <input type="checkbox" id="account-sidebar-toggle" class="dashboard-sidebar-control sr-only">
                            <label for="account-sidebar-toggle" class="dashboard-sidebar-toggle">
                                <span class="inline-flex min-w-0 items-center gap-3">
                                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-700 text-white shadow-sm">
                                        <x-ui.icon name="layout-dashboard" class="h-5 w-5" />
                                    </span>
                                    <span class="min-w-0 text-left">
                                        <span class="block truncate text-sm font-black tracking-normal text-slate-950">Menu</span>
                                        <span class="block truncate text-xs font-bold text-emerald-800">{{ $user->name }}</span>
                                    </span>
                                </span>
                                <x-ui.icon name="chevron-right" class="dashboard-sidebar-toggle-icon h-4 w-4 text-emerald-900" />
                            </label>

                            <div class="dashboard-sidebar-menu">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 p-3">
                                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-700 text-white shadow-sm">
                                        <x-ui.icon name="layout-dashboard" class="h-5 w-5" />
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block truncate text-sm font-black tracking-normal text-slate-950">My dashboard</span>
                                        <span class="block truncate text-xs font-bold text-emerald-800">{{ $user->name }}</span>
                                    </span>
                                </a>

                                <nav class="mt-5 space-y-5">
                                    @foreach ($accountSidebarMenu as $group)
                                        <section>
                                            <p class="px-2 text-[0.68rem] font-black uppercase tracking-[0.18em] text-slate-400">{{ $group['label'] }}</p>
                                            <div class="mt-2 space-y-1">
                                                @foreach ($group['items'] as $item)
                                                    <a href="{{ $item['url'] }}" class="dashboard-menu-link {{ $item['is_active'] ? 'dashboard-menu-link-active' : '' }}">
                                                        <x-ui.icon :name="$item['icon']" class="h-4 w-4" />
                                                        <span>{{ $item['label'] }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </section>
                                    @endforeach
                                </nav>
                            </div>
                        </div>
                    </div>
                </aside>
            @endauth

            <main class="app-main w-full min-w-0">
                @if (session('status'))
                    <div class="sr-only" data-mannarise-toast="{{ session('status') }}"></div>
                @endif

                @if (! request()->routeIs('home') && count($seo['breadcrumbs'] ?? []) > 1)
                    <nav aria-label="Breadcrumb" class="mb-4 flex flex-wrap items-center gap-2 text-sm font-bold text-slate-500">
                        @foreach ($seo['breadcrumbs'] as $index => $breadcrumb)
                            @if ($index > 0)
                                <x-ui.icon name="chevron-right" class="h-3.5 w-3.5 text-slate-400" />
                            @endif

                            @if (! empty($breadcrumb['url']) && ! $loop->last)
                                <a href="{{ $breadcrumb['url'] }}" class="hover:text-emerald-800">{{ $breadcrumb['label'] }}</a>
                            @else
                                <span class="{{ $loop->last ? 'text-slate-800' : '' }}">{{ $breadcrumb['label'] }}</span>
                            @endif
                        @endforeach
                    </nav>
                @endif

                {{ $slot }}
            </main>
        </div>

        <div data-toast-region class="pointer-events-none fixed right-3 top-[calc(5rem+env(safe-area-inset-top))] z-[70] flex w-[min(24rem,calc(100vw-1.5rem))] flex-col gap-2 sm:right-5 lg:top-24" aria-live="polite" aria-atomic="true"></div>

        <nav class="mobile-bottom-nav">
            <div class="mx-auto grid max-w-lg grid-cols-5 gap-1.5">
                @auth
                    <a href="{{ route('dashboard') }}" class="mobile-tab {{ request()->routeIs('dashboard') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="layout-dashboard" />
                        <span>{{ $navCopy['dashboard'] }}</span>
                    </a>
                    <a href="{{ $bibleUrl }}" class="mobile-tab {{ request()->routeIs('bible') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="book-open" />
                        <span>{{ $navCopy['bible'] }}</span>
                    </a>
                    <a href="{{ $dailyUrl }}" class="mobile-tab {{ request()->routeIs('daily.*') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="star" />
                        <span>{{ $navCopy['daily'] }}</span>
                    </a>
                    <a href="{{ route('journal.index') }}" class="mobile-tab {{ request()->routeIs('journal.*') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="journal" />
                        <span>{{ $navCopy['journal'] }}</span>
                    </a>
                @else
                    <a href="{{ route('devotionals.index') }}" class="mobile-tab {{ request()->routeIs('devotionals.*') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="sparkles" />
                        <span>{{ $navCopy['devotionals'] }}</span>
                    </a>
                    <a href="{{ $bibleUrl }}" class="mobile-tab {{ request()->routeIs('bible') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="book-open" />
                        <span>{{ $navCopy['bible'] }}</span>
                    </a>
                    <a href="{{ $dailyUrl }}" class="mobile-tab {{ request()->routeIs('daily.*') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="star" />
                        <span>{{ $navCopy['daily'] }}</span>
                    </a>
                    <a href="{{ route('library.index') }}" class="mobile-tab {{ request()->routeIs('library.*') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="library" />
                        <span>Library</span>
                    </a>
                @endauth
                <details class="relative">
                    <summary class="mobile-tab list-none cursor-pointer [&::-webkit-details-marker]:hidden {{ request()->routeIs(...$mobileMoreActiveRoutes) ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="more-horizontal" />
                        <span>{{ $navCopy['more'] }}</span>
                    </summary>

                    <div class="mobile-more-panel">
                        @foreach ($mobileMoreGroups as $group)
                            <div class="mb-3 last:mb-0">
                                <p class="mb-2 px-2 text-[0.65rem] font-bold uppercase tracking-[0.18em] text-slate-400">{{ $group['label'] }}</p>

                                <div class="space-y-1">
                                    @foreach ($group['links'] as $link)
                                        <a href="{{ $link['url'] ?? route($link['route']) }}" class="mobile-more-link {{ request()->routeIs(...$link['active']) ? 'mobile-more-link-active' : '' }}">
                                            <x-ui.icon :name="$link['icon']" class="h-4 w-4" />
                                            <span>{{ $link['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </details>
            </div>
        </nav>

        <div data-install-banner class="fixed inset-x-3 bottom-[calc(6.2rem+env(safe-area-inset-bottom))] z-40 mx-auto hidden max-w-lg rounded-2xl border border-emerald-200 bg-white p-3 shadow-2xl lg:hidden">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-700 text-white">
                    <x-ui.icon name="download" class="h-5 w-5" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-black tracking-normal text-slate-950">Install MannaRise</p>
                    <p class="text-xs font-bold text-slate-500">Open faster and keep spiritual tools close.</p>
                </div>
                <button type="button" data-install-now class="rounded-full bg-emerald-700 px-3 py-2 text-xs font-black text-white">Install</button>
                <button type="button" data-install-dismiss class="rounded-full border border-slate-200 px-3 py-2 text-xs font-black text-slate-600">Later</button>
            </div>
        </div>

        <footer class="app-footer">
            @php
                $footerGroups = [
                    [
                        'label' => 'MannaRise',
                        'links' => [
                            ['label' => 'About', 'url' => route('about')],
                            ['label' => 'Contact', 'url' => route('contact')],
                            ['label' => $navCopy['daily'], 'url' => $dailyUrl],
                            ['label' => $navCopy['bible'], 'url' => $bibleUrl],
                        ],
                    ],
                    [
                        'label' => $navCopy['explore'],
                        'links' => [
                            ['label' => 'Resources', 'url' => route('resources.index')],
                            ['label' => 'Plans', 'url' => route('devotional-plans.index')],
                            ['label' => 'Library', 'url' => route('library.index')],
                            ['label' => 'Cards', 'url' => route('scripture-cards.index')],
                        ],
                    ],
                    [
                        'label' => $navCopy['prayer'],
                        'links' => [
                            ['label' => $navCopy['guided_prayer'], 'url' => route('prayer-sessions.index')],
                            ['label' => $navCopy['prayer_rooms'], 'url' => route('prayer-rooms.index')],
                            ['label' => $navCopy['prayer_wall'], 'url' => route('prayer-requests.wall')],
                            ['label' => $navCopy['request_prayer'], 'url' => route('prayer-requests.submit')],
                        ],
                    ],
                    [
                        'label' => 'Discovery',
                        'links' => [
                            ['label' => 'Sitemap', 'url' => route('seo.sitemap')],
                            ['label' => 'llms.txt', 'url' => route('seo.llms')],
                            ['label' => 'RSS', 'url' => route('seo.feed')],
                            ['label' => 'AI', 'url' => route('seo.ai')],
                        ],
                    ],
                ];
            @endphp

            <div class="footer-inner">
                <div class="footer-brand">
                    <a href="{{ $homeUrl }}" class="footer-brand-mark">
                        <span><x-ui.icon name="sparkles" class="h-5 w-5" /></span>
                        <strong>MannaRise</strong>
                    </a>
                    <p>MannaRise devotional and spiritual growth platform.</p>
                    <div class="footer-language-list" aria-label="{{ $navCopy['choose_language'] }}">
                        @foreach ($languageOptions as $languageOption)
                            <a href="{{ $languageOption['switch_url'] }}" class="footer-language-link {{ $languageOption['current'] ? 'footer-language-link-active' : '' }}">
                                {{ strtoupper($languageOption['code']) }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="footer-link-grid">
                    @foreach ($footerGroups as $group)
                        <section class="footer-link-group">
                            <h2>{{ $group['label'] }}</h2>

                            <div>
                                @foreach ($group['links'] as $link)
                                    <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; {{ now()->year }} MannaRise</span>
                <span>{{ $activeLanguage['native_name'] }} · {{ strtoupper($activeLocale) }}</span>
                <a href="https://dayoebe.github.io" target="_blank" rel="noopener">Wireless Terminal</a>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
