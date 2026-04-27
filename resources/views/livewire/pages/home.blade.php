<div class="space-y-10">
    <section class="grid gap-6 lg:grid-cols-[1.4fr_0.8fr] lg:items-stretch">
        <div class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm sm:p-8">
            <p class="text-sm font-semibold uppercase tracking-normal text-emerald-800">Daily devotionals</p>
            <h1 class="mt-3 max-w-3xl text-4xl font-semibold text-stone-950 sm:text-5xl">MannaRise</h1>
            <p class="mt-4 max-w-2xl text-base leading-7 text-stone-600">
                Scripture-led devotionals, reflection journaling, prayer requests, testimonies, and steady reading rhythms in one place.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('devotionals.index') }}" class="rounded-md bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">Read devotionals</a>
                <a href="{{ route('prayer-requests.submit') }}" class="rounded-md border border-stone-300 px-4 py-2.5 text-sm font-semibold text-stone-800 hover:bg-stone-100">Submit prayer request</a>
            </div>
        </div>

        <div class="rounded-lg border border-emerald-200 bg-emerald-900 p-6 text-white shadow-sm">
            <p class="text-sm font-medium text-emerald-100">Featured reading</p>
            @if ($featuredDevotional)
                <h2 class="mt-3 text-2xl font-semibold">{{ $featuredDevotional->title }}</h2>
                <p class="mt-3 text-sm leading-6 text-emerald-50">{{ \Illuminate\Support\Str::limit(strip_tags($featuredDevotional->content), 170) }}</p>
                <a href="{{ route('devotionals.show', $featuredDevotional->slug) }}" class="mt-5 inline-flex rounded-md bg-white px-4 py-2 text-sm font-semibold text-emerald-900 hover:bg-emerald-50">Open devotional</a>
            @else
                <h2 class="mt-3 text-2xl font-semibold">No featured devotional yet</h2>
                <p class="mt-3 text-sm leading-6 text-emerald-50">Publish a devotional from the admin dashboard to feature it here.</p>
            @endif
        </div>
    </section>

    <section>
        <div class="mb-4 flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-stone-950">Latest devotionals</h2>
                <p class="mt-1 text-sm text-stone-600">Fresh readings for reflection and prayer.</p>
            </div>
            <a href="{{ route('devotionals.index') }}" class="text-sm font-semibold text-emerald-800 hover:underline">View all</a>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            @forelse ($latestDevotionals as $devotional)
                <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-normal text-emerald-800">{{ $devotional->category?->name }}</p>
                    <h3 class="mt-2 text-lg font-semibold text-stone-950">{{ $devotional->title }}</h3>
                    <p class="mt-2 text-sm leading-6 text-stone-600">{{ \Illuminate\Support\Str::limit(strip_tags($devotional->content), 130) }}</p>
                    <a href="{{ route('devotionals.show', $devotional->slug) }}" class="mt-4 inline-flex text-sm font-semibold text-emerald-800 hover:underline">Read</a>
                </article>
            @empty
                <div class="rounded-lg border border-dashed border-stone-300 bg-white p-6 text-sm text-stone-600 md:col-span-3">No published devotionals yet.</div>
            @endforelse
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <div>
            <h2 class="text-2xl font-semibold text-stone-950">Topics</h2>
            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                @forelse ($categories as $category)
                    <a href="{{ route('devotionals.index', ['category' => $category->slug]) }}" class="rounded-lg border border-stone-200 bg-white p-4 shadow-sm hover:border-emerald-300">
                        <span class="block font-semibold text-stone-950">{{ $category->name }}</span>
                        <span class="mt-1 block text-sm text-stone-600">{{ $category->devotionals_count }} readings</span>
                    </a>
                @empty
                    <p class="rounded-lg border border-dashed border-stone-300 bg-white p-4 text-sm text-stone-600">No active categories yet.</p>
                @endforelse
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-semibold text-stone-950">Recent testimonies</h2>
            <div class="mt-4 space-y-3">
                @forelse ($testimonies as $testimony)
                    <article class="rounded-lg border border-stone-200 bg-white p-4 shadow-sm">
                        <h3 class="font-semibold text-stone-950">{{ $testimony->title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-stone-600">{{ \Illuminate\Support\Str::limit($testimony->body, 140) }}</p>
                        <p class="mt-3 text-xs font-medium text-stone-500">{{ $testimony->is_anonymous ? 'Anonymous' : ($testimony->name ?: 'MannaRise reader') }}</p>
                    </article>
                @empty
                    <p class="rounded-lg border border-dashed border-stone-300 bg-white p-4 text-sm text-stone-600">Approved testimonies will appear here.</p>
                @endforelse
            </div>
        </div>
    </section>
</div>
