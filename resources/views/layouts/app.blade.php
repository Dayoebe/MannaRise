<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <meta name="theme-color" content="#047857">
        <meta name="color-scheme" content="light">
        <meta name="application-name" content="MannaRise">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="MannaRise">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">

        <title>{{ $title ?? config('app.name') }}</title>

        <link rel="manifest" href="/manifest.webmanifest">
        <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
        <link rel="mask-icon" href="/icons/icon-512.svg" color="#047857">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="app-shell flex min-h-screen flex-col bg-mist-50 text-slate-950 antialiased">
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
                            <a href="{{ route('dashboard') }}" class="btn-secondary px-3" title="Dashboard">
                                <x-ui.icon name="layout-dashboard" class="h-4 w-4" />
                                <span class="hidden sm:inline">Dashboard</span>
                            </a>
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

                <nav class="hidden gap-2 text-sm lg:flex lg:flex-wrap">
                    <a href="{{ route('devotionals.index') }}" class="nav-pill {{ request()->routeIs('devotionals.*') ? 'nav-pill-active' : '' }}"><x-ui.icon name="sparkles" class="h-4 w-4" /> Devotionals</a>
                    <a href="{{ route('bible') }}" class="nav-pill {{ request()->routeIs('bible') ? 'nav-pill-active' : '' }}"><x-ui.icon name="book-open" class="h-4 w-4" /> Bible</a>
                    <a href="{{ route('daily.index') }}" class="nav-pill {{ request()->routeIs('daily.*') ? 'nav-pill-active' : '' }}"><x-ui.icon name="star" class="h-4 w-4" /> Daily</a>
                    <a href="{{ route('devotional-plans.index') }}" class="nav-pill {{ request()->routeIs('devotional-plans.*') ? 'nav-pill-active' : '' }}"><x-ui.icon name="route" class="h-4 w-4" /> Plans</a>
                    <a href="{{ route('library.index') }}" class="nav-pill {{ request()->routeIs('library.*') ? 'nav-pill-active' : '' }}"><x-ui.icon name="library" class="h-4 w-4" /> Library</a>
                    <a href="{{ route('memory-verses.index') }}" class="nav-pill {{ request()->routeIs('memory-verses.*') ? 'nav-pill-active' : '' }}"><x-ui.icon name="bookmark" class="h-4 w-4" /> Memory</a>
                    <a href="{{ route('scripture-cards.index') }}" class="nav-pill {{ request()->routeIs('scripture-cards.*') ? 'nav-pill-active' : '' }}"><x-ui.icon name="book-open" class="h-4 w-4" /> Cards</a>
                    <a href="{{ route('prayer-sessions.index') }}" class="nav-pill {{ request()->routeIs('prayer-sessions.*') ? 'nav-pill-active' : '' }}"><x-ui.icon name="heart" class="h-4 w-4" /> Guided Prayer</a>
                    <a href="{{ route('audio-devotionals.index') }}" class="nav-pill {{ request()->routeIs('audio-devotionals.*') ? 'nav-pill-active' : '' }}"><x-ui.icon name="headphones" class="h-4 w-4" /> Audio</a>
                    <a href="{{ route('prayer-rooms.index') }}" class="nav-pill {{ request()->routeIs('prayer-rooms.*') ? 'nav-pill-active' : '' }}"><x-ui.icon name="users" class="h-4 w-4" /> Prayer Rooms</a>
                    <a href="{{ route('prayer-requests.wall') }}" class="nav-pill {{ request()->routeIs('prayer-requests.wall') ? 'nav-pill-active' : '' }}"><x-ui.icon name="heart" class="h-4 w-4" /> Prayer Wall</a>
                    <a href="{{ route('prayer-requests.submit') }}" class="nav-pill {{ request()->routeIs('prayer-requests.submit') ? 'nav-pill-active' : '' }}"><x-ui.icon name="send" class="h-4 w-4" /> Request</a>
                    <a href="{{ route('testimonies.index') }}" class="nav-pill {{ request()->routeIs('testimonies.*') ? 'nav-pill-active' : '' }}"><x-ui.icon name="message-circle" class="h-4 w-4" /> Testimonies</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="nav-pill {{ request()->routeIs('dashboard') || request()->routeIs('journal.*') || request()->routeIs('favorites.*') ? 'nav-pill-active' : '' }}"><x-ui.icon name="layout-dashboard" class="h-4 w-4" /> Dashboard</a>
                        @if (auth()->user()->hasAdminAccess())
                            <a href="{{ route('admin.dashboard') }}" class="nav-pill {{ request()->routeIs('admin.*') ? 'nav-pill-active' : '' }}"><x-ui.icon name="shield" class="h-4 w-4" /> Admin</a>
                        @endif
                    @endauth
                </nav>

                @auth
                    <nav class="hidden gap-2 text-sm lg:flex lg:flex-wrap">
                        <a href="{{ route('journal.index') }}" class="subnav-pill {{ request()->routeIs('journal.*') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="journal" class="h-4 w-4" /> Journal</a>
                        <a href="{{ route('favorites.index') }}" class="subnav-pill {{ request()->routeIs('favorites.*') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="bookmark" class="h-4 w-4" /> Favorites</a>
                        <a href="{{ route('growth-path.index') }}" class="subnav-pill {{ request()->routeIs('growth-path.*') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="route" class="h-4 w-4" /> Path</a>
                        <a href="{{ route('reminders.settings') }}" class="subnav-pill {{ request()->routeIs('reminders.*') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="star" class="h-4 w-4" /> Reminders</a>
                        <a href="{{ route('community-groups.index') }}" class="subnav-pill {{ request()->routeIs('community-groups.*') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="users" class="h-4 w-4" /> Groups</a>
                        @if (auth()->user()->hasAdminAccess())
                            <a href="{{ route('admin.categories') }}" class="subnav-pill {{ request()->routeIs('admin.categories') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="bookmark" class="h-4 w-4" /> Categories</a>
                            <a href="{{ route('admin.devotionals') }}" class="subnav-pill {{ request()->routeIs('admin.devotionals') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="sparkles" class="h-4 w-4" /> Devotionals</a>
                            <a href="{{ route('admin.featured-content') }}" class="subnav-pill {{ request()->routeIs('admin.featured-content') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="star" class="h-4 w-4" /> Featured</a>
                            <a href="{{ route('admin.moderation') }}" class="subnav-pill {{ request()->routeIs('admin.moderation') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="message-circle" class="h-4 w-4" /> Queue</a>
                            <a href="{{ route('admin.prayer-requests') }}" class="subnav-pill {{ request()->routeIs('admin.prayer-requests') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="heart" class="h-4 w-4" /> Requests</a>
                            <a href="{{ route('admin.testimonies') }}" class="subnav-pill {{ request()->routeIs('admin.testimonies') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="message-circle" class="h-4 w-4" /> Moderation</a>
                            <a href="{{ route('admin.engagement') }}" class="subnav-pill {{ request()->routeIs('admin.engagement') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="bar-chart" class="h-4 w-4" /> Engagement</a>
                            @if (auth()->user()->canDo('manage-audio-devotionals'))
                                <a href="{{ route('admin.audio-devotionals') }}" class="subnav-pill {{ request()->routeIs('admin.audio-devotionals') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="headphones" class="h-4 w-4" /> Audio</a>
                            @endif
                            @if (auth()->user()->canDo('manage-roles'))
                                <a href="{{ route('admin.roles') }}" class="subnav-pill {{ request()->routeIs('admin.roles') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="shield" class="h-4 w-4" /> Roles</a>
                            @endif
                            <a href="{{ route('admin.settings') }}" class="subnav-pill {{ request()->routeIs('admin.settings') ? 'subnav-pill-active' : '' }}"><x-ui.icon name="settings" class="h-4 w-4" /> Settings</a>
                        @endif
                    </nav>
                @endauth
            </div>
        </header>

        <main class="app-main mx-auto w-full max-w-7xl flex-1 px-3 pb-28 pt-5 sm:px-5 sm:pt-8 lg:px-8 lg:pb-8">
            @if (session('status'))
                <div class="mb-6 app-surface flex items-center gap-2 border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-900">
                    <x-ui.icon name="sparkles" class="h-4 w-4" /> {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </main>

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
                    <summary class="mobile-tab list-none cursor-pointer [&::-webkit-details-marker]:hidden {{ request()->routeIs('devotional-plans.*') || request()->routeIs('memory-verses.*') || request()->routeIs('scripture-cards.*') || request()->routeIs('prayer-sessions.*') || request()->routeIs('audio-devotionals.*') || request()->routeIs('prayer-rooms.*') || request()->routeIs('prayer-requests.*') || request()->routeIs('testimonies.*') || request()->routeIs('community-groups.*') || request()->routeIs('reminders.*') ? 'mobile-tab-active' : '' }}">
                        <x-ui.icon name="more-horizontal" />
                        <span>More</span>
                    </summary>

                    <div class="mobile-more-panel">
                        <a href="{{ route('devotional-plans.index') }}" class="mobile-more-link {{ request()->routeIs('devotional-plans.*') ? 'mobile-more-link-active' : '' }}">
                            <x-ui.icon name="route" class="h-4 w-4" />
                            <span>Plans</span>
                        </a>
                        <a href="{{ route('memory-verses.index') }}" class="mobile-more-link {{ request()->routeIs('memory-verses.*') ? 'mobile-more-link-active' : '' }}">
                            <x-ui.icon name="bookmark" class="h-4 w-4" />
                            <span>Memory verses</span>
                        </a>
                        <a href="{{ route('scripture-cards.index') }}" class="mobile-more-link {{ request()->routeIs('scripture-cards.*') ? 'mobile-more-link-active' : '' }}">
                            <x-ui.icon name="book-open" class="h-4 w-4" />
                            <span>Scripture cards</span>
                        </a>
                        <a href="{{ route('prayer-sessions.index') }}" class="mobile-more-link {{ request()->routeIs('prayer-sessions.*') ? 'mobile-more-link-active' : '' }}">
                            <x-ui.icon name="heart" class="h-4 w-4" />
                            <span>Guided prayer</span>
                        </a>
                        <a href="{{ route('audio-devotionals.index') }}" class="mobile-more-link {{ request()->routeIs('audio-devotionals.*') ? 'mobile-more-link-active' : '' }}">
                            <x-ui.icon name="headphones" class="h-4 w-4" />
                            <span>Audio devotionals</span>
                        </a>
                        @auth
                            <a href="{{ route('community-groups.index') }}" class="mobile-more-link {{ request()->routeIs('community-groups.*') ? 'mobile-more-link-active' : '' }}">
                                <x-ui.icon name="users" class="h-4 w-4" />
                                <span>Groups</span>
                            </a>
                            <a href="{{ route('reminders.settings') }}" class="mobile-more-link {{ request()->routeIs('reminders.*') ? 'mobile-more-link-active' : '' }}">
                                <x-ui.icon name="star" class="h-4 w-4" />
                                <span>Reminders</span>
                            </a>
                        @endauth
                        <a href="{{ route('prayer-rooms.index') }}" class="mobile-more-link {{ request()->routeIs('prayer-rooms.*') ? 'mobile-more-link-active' : '' }}">
                            <x-ui.icon name="users" class="h-4 w-4" />
                            <span>Prayer Rooms</span>
                        </a>
                        <a href="{{ route('prayer-requests.wall') }}" class="mobile-more-link {{ request()->routeIs('prayer-requests.wall') ? 'mobile-more-link-active' : '' }}">
                            <x-ui.icon name="heart" class="h-4 w-4" />
                            <span>Prayer Wall</span>
                        </a>
                        <a href="{{ route('prayer-requests.submit') }}" class="mobile-more-link {{ request()->routeIs('prayer-requests.submit') ? 'mobile-more-link-active' : '' }}">
                            <x-ui.icon name="send" class="h-4 w-4" />
                            <span>Request</span>
                        </a>
                        <a href="{{ route('testimonies.index') }}" class="mobile-more-link {{ request()->routeIs('testimonies.*') ? 'mobile-more-link-active' : '' }}">
                            <x-ui.icon name="message-circle" class="h-4 w-4" />
                            <span>Testimonies</span>
                        </a>
                    </div>
                </details>
            </div>
        </nav>

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
