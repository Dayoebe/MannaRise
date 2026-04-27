<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="min-h-screen bg-stone-50 text-stone-950 antialiased">
        <header class="border-b border-stone-200 bg-white/95">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ route('home') }}" class="text-xl font-semibold tracking-normal text-emerald-800">
                        MannaRise
                    </a>

                    <nav class="flex flex-wrap items-center gap-2 text-sm font-medium text-stone-700">
                        <a href="{{ route('devotionals.index') }}" class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('devotionals.*') ? 'bg-emerald-50 text-emerald-800' : '' }}">Devotionals</a>
                        <a href="{{ route('prayer-requests.submit') }}" class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('prayer-requests.*') ? 'bg-emerald-50 text-emerald-800' : '' }}">Prayer</a>
                        <a href="{{ route('testimonies.submit') }}" class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('testimonies.*') ? 'bg-emerald-50 text-emerald-800' : '' }}">Testimony</a>

                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('dashboard') || request()->routeIs('journal.*') || request()->routeIs('favorites.*') ? 'bg-emerald-50 text-emerald-800' : '' }}">Dashboard</a>
                            @if (auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="rounded-md px-3 py-2 hover:bg-stone-100 {{ request()->routeIs('admin.*') ? 'bg-emerald-50 text-emerald-800' : '' }}">Admin</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="rounded-md border border-stone-300 px-3 py-2 hover:bg-stone-100">Log out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="rounded-md px-3 py-2 hover:bg-stone-100">Log in</a>
                            <a href="{{ route('register') }}" class="rounded-md bg-emerald-700 px-3 py-2 text-white hover:bg-emerald-800">Create account</a>
                        @endauth
                    </nav>
                </div>

                @auth
                    <nav class="flex flex-wrap gap-2 text-sm text-stone-600">
                        <a href="{{ route('journal.index') }}" class="rounded-md px-3 py-1.5 hover:bg-stone-100 {{ request()->routeIs('journal.*') ? 'bg-white text-stone-950 shadow-sm ring-1 ring-stone-200' : '' }}">Journal</a>
                        <a href="{{ route('favorites.index') }}" class="rounded-md px-3 py-1.5 hover:bg-stone-100 {{ request()->routeIs('favorites.*') ? 'bg-white text-stone-950 shadow-sm ring-1 ring-stone-200' : '' }}">Favorites</a>
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.categories') }}" class="rounded-md px-3 py-1.5 hover:bg-stone-100 {{ request()->routeIs('admin.categories') ? 'bg-white text-stone-950 shadow-sm ring-1 ring-stone-200' : '' }}">Categories</a>
                            <a href="{{ route('admin.devotionals') }}" class="rounded-md px-3 py-1.5 hover:bg-stone-100 {{ request()->routeIs('admin.devotionals') ? 'bg-white text-stone-950 shadow-sm ring-1 ring-stone-200' : '' }}">Devotionals</a>
                            <a href="{{ route('admin.prayer-requests') }}" class="rounded-md px-3 py-1.5 hover:bg-stone-100 {{ request()->routeIs('admin.prayer-requests') ? 'bg-white text-stone-950 shadow-sm ring-1 ring-stone-200' : '' }}">Requests</a>
                            <a href="{{ route('admin.testimonies') }}" class="rounded-md px-3 py-1.5 hover:bg-stone-100 {{ request()->routeIs('admin.testimonies') ? 'bg-white text-stone-950 shadow-sm ring-1 ring-stone-200' : '' }}">Moderation</a>
                            <a href="{{ route('admin.engagement') }}" class="rounded-md px-3 py-1.5 hover:bg-stone-100 {{ request()->routeIs('admin.engagement') ? 'bg-white text-stone-950 shadow-sm ring-1 ring-stone-200' : '' }}">Engagement</a>
                        @endif
                    </nav>
                @endauth
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </main>

        <footer class="border-t border-stone-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-stone-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <span>MannaRise devotional and spiritual growth platform.</span>
                <span>{{ now()->year }}</span>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
