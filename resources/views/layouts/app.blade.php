@php
    $seo = \App\Support\Seo::meta($seo ?? []);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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

        <title>{{ $seo['title'] }}</title>
        <link rel="canonical" href="{{ $seo['canonical'] }}">

        <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">
        <meta property="og:site_name" content="{{ $seo['site_name'] }}">
        <meta property="og:type" content="{{ $seo['type'] }}">
        <meta property="og:title" content="{{ $seo['title'] }}">
        <meta property="og:description" content="{{ $seo['description'] }}">
        <meta property="og:url" content="{{ $seo['canonical'] }}">
        <meta property="og:image" content="{{ $seo['image'] }}">
        <meta property="og:image:alt" content="{{ $seo['raw_title'] }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $seo['title'] }}">
        <meta name="twitter:description" content="{{ $seo['description'] }}">
        <meta name="twitter:image" content="{{ $seo['image'] }}">
        @if ($seo['twitter_site'])
            <meta name="twitter:site" content="{{ $seo['twitter_site'] }}">
        @endif

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
                    <a href="{{ route('home') }}" class="app-brand group inline-flex min-h-12 items-center gap-3 rounded-2xl border border-emerald-200 bg-white px-3 py-2 shadow-sm transition hover:border-emerald-300">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-700 text-white">
                            <x-ui.icon name="sparkles" class="h-5 w-5" />
                        </span>
                        <span>
                            <span class="block font-display text-lg font-bold tracking-normal text-emerald-900">MannaRise</span>
                            <span class="block text-xs font-semibold text-slate-500">grow daily</span>
                        </span>
                    </a>

                    <div class="flex shrink-0 items-center gap-2">
                        @auth
                            @unless ($usesAccountSidebar)
                                <a href="{{ route('dashboard') }}" class="btn-secondary px-3" title="Dashboard">
                                    <x-ui.icon name="layout-dashboard" class="h-4 w-4" />
                                    <span class="hidden sm:inline">Dashboard</span>
                                </a>
                            @endunless
                            @if (auth()->user()->hasAdminAccess())
                                <a href="{{ route('admin.dashboard') }}" class="btn-secondary px-3" title="Admin">
                                    <x-ui.icon name="shield" class="h-4 w-4" />
                                    <span class="hidden sm:inline">{{ auth()->user()->is_super_admin ? 'Super Admin' : 'Admin' }}</span>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-secondary px-3" title="Log out">
                                    <x-ui.icon name="log-out" class="h-4 w-4" />
                                    <span class="hidden sm:inline">Log out</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-secondary px-3">
                                <x-ui.icon name="log-in" class="h-4 w-4" />
                                <span class="hidden sm:inline">Log in</span>
                            </a>
                            <a href="{{ route('register') }}" class="btn-primary px-3">
                                <x-ui.icon name="sparkles" class="h-4 w-4" />
                                <span class="hidden sm:inline">Join</span>
                            </a>
                        @endauth
                    </div>
                </div>

                @php
                    $mainLinks = [
                        ['label' => 'Daily', 'route' => 'daily.index', 'icon' => 'star', 'active' => ['daily.*']],
                        ['label' => 'Devotionals', 'route' => 'devotionals.index', 'icon' => 'sparkles', 'active' => ['devotionals.*']],
                        ['label' => 'Bible', 'route' => 'bible', 'icon' => 'book-open', 'active' => ['bible']],
                        ['label' => 'Resources', 'route' => 'resources.index', 'icon' => 'library', 'active' => ['resources.*']],
                        ['label' => 'Prayer', 'route' => 'prayer-sessions.index', 'icon' => 'heart', 'active' => ['prayer-sessions.*', 'prayer-rooms.*', 'prayer-requests.*']],
                    ];

                    if ($user) {
                        $mainLinks[] = ['label' => 'Journal', 'route' => 'journal.index', 'icon' => 'journal', 'active' => ['journal.*']];
                    }

                    $exploreLinks = [
                        ['label' => 'Plans', 'route' => 'devotional-plans.index', 'icon' => 'route', 'active' => ['devotional-plans.*']],
                        ['label' => 'Library', 'route' => 'library.index', 'icon' => 'library', 'active' => ['library.*']],
                        ['label' => 'Hub Books', 'route' => 'resources.books', 'icon' => 'library', 'active' => ['resources.books']],
                        ['label' => 'Hub Audio', 'route' => 'resources.audio', 'icon' => 'headphones', 'active' => ['resources.audio']],
                        ['label' => 'Memory', 'route' => 'memory-verses.index', 'icon' => 'bookmark', 'active' => ['memory-verses.*']],
                        ['label' => 'Cards', 'route' => 'scripture-cards.index', 'icon' => 'book-open', 'active' => ['scripture-cards.*']],
                        ['label' => 'Audio', 'route' => 'audio-devotionals.index', 'icon' => 'headphones', 'active' => ['audio-devotionals.*']],
                        ['label' => 'Testimonies', 'route' => 'testimonies.index', 'icon' => 'message-circle', 'active' => ['testimonies.*']],
                    ];

                    if ($user) {
                        $exploreLinks[] = ['label' => 'Groups', 'route' => 'community-groups.index', 'icon' => 'users', 'active' => ['community-groups.*']];
                    }

                    $accountLinks = [];

                    if ($user) {
                        $accountLinks = [
                            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'layout-dashboard', 'active' => ['dashboard']],
                            ['label' => 'Favorites', 'route' => 'favorites.index', 'icon' => 'bookmark', 'active' => ['favorites.*']],
                            ['label' => 'Path', 'route' => 'growth-path.index', 'icon' => 'route', 'active' => ['growth-path.*']],
                            ['label' => 'Reminders', 'route' => 'reminders.settings', 'icon' => 'star', 'active' => ['reminders.*']],
                        ];
                    }

                    $adminLinks = [];

                    if ($user?->hasAdminAccess()) {
                        $adminLinks = [
                            ['label' => 'Admin Home', 'route' => 'admin.dashboard', 'icon' => 'shield', 'active' => ['admin.dashboard']],
                            ['label' => 'Content', 'route' => 'admin.devotionals', 'icon' => 'sparkles', 'active' => ['admin.categories', 'admin.devotionals', 'admin.featured-content']],
                            ['label' => 'Resource Admin', 'route' => 'admin.resource-items', 'icon' => 'library', 'active' => ['admin.resource-categories', 'admin.resource-items', 'admin.daily-devotions']],
                            ['label' => 'Moderation', 'route' => 'admin.moderation', 'icon' => 'message-circle', 'active' => ['admin.moderation', 'admin.prayer-requests', 'admin.testimonies']],
                            ['label' => 'Engagement', 'route' => 'admin.engagement', 'icon' => 'bar-chart', 'active' => ['admin.engagement']],
                            ['label' => 'Settings', 'route' => 'admin.settings', 'icon' => 'settings', 'active' => ['admin.settings']],
                        ];

                        if ($user->canDo('manage-audio-devotionals')) {
                            $adminLinks[] = ['label' => 'Audio Admin', 'route' => 'admin.audio-devotionals', 'icon' => 'headphones', 'active' => ['admin.audio-devotionals']];
                        }

                        if ($user->canDo('manage-roles')) {
                            $adminLinks[] = ['label' => 'Roles', 'route' => 'admin.roles', 'icon' => 'shield', 'active' => ['admin.roles']];
                        }
                    }

                    $desktopNavGroups = array_filter([
                        ['label' => 'Main', 'links' => $mainLinks],
                        ['label' => 'Explore', 'links' => $exploreLinks],
                        ['label' => 'My Space', 'links' => $accountLinks],
                        ['label' => 'Admin', 'links' => $adminLinks],
                    ], fn (array $group) => count($group['links']) > 0);

                    $mobileMoreGroups = [
                        ['label' => 'Explore', 'links' => $exploreLinks],
                        ['label' => 'Prayer', 'links' => [
                            ['label' => 'Guided Prayer', 'route' => 'prayer-sessions.index', 'icon' => 'heart', 'active' => ['prayer-sessions.*']],
                            ['label' => 'Prayer Rooms', 'route' => 'prayer-rooms.index', 'icon' => 'users', 'active' => ['prayer-rooms.*']],
                            ['label' => 'Prayer Wall', 'route' => 'prayer-requests.wall', 'icon' => 'heart', 'active' => ['prayer-requests.wall']],
                            ['label' => 'Request Prayer', 'route' => 'prayer-requests.submit', 'icon' => 'send', 'active' => ['prayer-requests.submit']],
                        ]],
                    ];

                    if ($user) {
                        $mobileMoreGroups[] = ['label' => 'My Space', 'links' => $accountLinks];
                    }

                    if ($user?->hasAdminAccess()) {
                        $mobileMoreGroups[] = ['label' => 'Admin', 'links' => $adminLinks];
                    }

                    $mobileMoreActiveRoutes = collect($mobileMoreGroups)
                        ->flatMap(fn (array $group) => collect($group['links'])->flatMap(fn (array $link) => $link['active']))
                        ->all();
                @endphp

                @unless ($usesAccountSidebar)
                    <nav class="hidden flex-col gap-2 text-sm lg:flex">
                        @foreach ($desktopNavGroups as $group)
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="min-w-16 text-xs font-bold uppercase tracking-[0.18em] text-slate-400">{{ $group['label'] }}</span>

                                @foreach ($group['links'] as $link)
                                    <a href="{{ route($link['route']) }}" class="nav-pill {{ request()->routeIs(...$link['active']) ? 'nav-pill-active' : '' }}">
                                        <x-ui.icon :name="$link['icon']" class="h-4 w-4" /> {{ $link['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    </nav>
                @endunless
            </div>
        </header>

        <div class="app-body mx-auto grid w-full max-w-7xl flex-1 gap-6 px-3 pt-5 sm:px-5 sm:pt-8 lg:px-8 {{ $usesAccountSidebar ? 'pb-8 lg:grid-cols-[17rem_minmax(0,1fr)] lg:items-start' : 'pb-28 lg:pb-8' }}">
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
                    <div class="mb-6 app-surface flex items-center gap-2 border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-900">
                        <x-ui.icon name="sparkles" class="h-4 w-4" /> {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        @unless ($usesAccountSidebar)
            <nav class="mobile-bottom-nav">
                <div class="mx-auto grid max-w-lg grid-cols-5 gap-2">
                    <a href="{{ route('devotionals.index') }}" class="mobile-tab {{ request()->routeIs('devotionals.*') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="sparkles" />
                        <span>Devotionals</span>
                    </a>
                    <a href="{{ route('bible') }}" class="mobile-tab {{ request()->routeIs('bible') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="book-open" />
                        <span>Bible</span>
                    </a>
                    <a href="{{ route('daily.index') }}" class="mobile-tab {{ request()->routeIs('daily.*') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="star" />
                        <span>Daily</span>
                    </a>
                    <a href="{{ route('library.index') }}" class="mobile-tab {{ request()->routeIs('library.*') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="library" />
                        <span>Library</span>
                    </a>
                    <details class="relative">
                        <summary class="mobile-tab list-none cursor-pointer [&::-webkit-details-marker]:hidden {{ request()->routeIs(...$mobileMoreActiveRoutes) ? 'mobile-tab-active' : '' }}">
                            <x-ui.icon name="more-horizontal" />
                            <span>More</span>
                        </summary>

                        <div class="mobile-more-panel">
                            @foreach ($mobileMoreGroups as $group)
                                <div class="mb-3 last:mb-0">
                                    <p class="mb-2 px-2 text-[0.65rem] font-bold uppercase tracking-[0.18em] text-slate-400">{{ $group['label'] }}</p>

                                    <div class="space-y-1">
                                        @foreach ($group['links'] as $link)
                                            <a href="{{ route($link['route']) }}" class="mobile-more-link {{ request()->routeIs(...$link['active']) ? 'mobile-more-link-active' : '' }}">
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
        @endunless

        <footer class="app-footer border-t border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-3 py-6 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-5 lg:px-8">
                <span class="inline-flex items-center gap-2 font-semibold text-slate-700"><x-ui.icon name="sparkles" class="h-4 w-4 text-emerald-800" /> MannaRise devotional and spiritual growth platform.</span>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                    <span class="h-3 w-3 rounded-full bg-sky-400"></span>
                    <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                    <span class="h-3 w-3 rounded-full bg-rose-400"></span>
                    <span class="ml-1">{{ now()->year }}</span>
                </div>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
