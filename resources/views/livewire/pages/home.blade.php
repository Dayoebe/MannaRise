<div class="space-y-8 sm:space-y-10">
    <section class="grid gap-4 lg:grid-cols-[minmax(0,1.45fr)_minmax(18rem,0.8fr)] lg:items-stretch">
        <div class="app-panel overflow-hidden border-emerald-200 p-0 sm:p-0">
            <div class="color-strip rounded-none">
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
            <div class="p-5 sm:p-8">
                <p class="app-eyebrow"><x-ui.icon name="sparkles" class="h-4 w-4" /> ✨ Daily devotionals</p>
                <h1 class="mt-4 max-w-3xl text-4xl font-black tracking-normal text-slate-950 sm:text-5xl">MannaRise</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600">
                    Scripture, prayer, testimonies, journaling, the Bible, and classic spiritual books gathered for steady daily growth.
                </p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <a href="{{ route('devotionals.index') }}" class="btn-primary w-full sm:w-auto"><x-ui.icon name="sparkles" class="h-4 w-4" /> Read devotionals ✨</a>
                    <a href="{{ route('bible') }}" class="btn-warm w-full sm:w-auto"><x-ui.icon name="book-open" class="h-4 w-4" /> Open Bible 📖</a>
                    <a href="{{ route('prayer-requests.submit') }}" class="btn-secondary w-full sm:w-auto"><x-ui.icon name="send" class="h-4 w-4" /> Prayer request 🕊️</a>
                </div>

                <div class="mt-7 grid gap-3 text-sm font-bold text-slate-800 sm:grid-cols-3">
                    <a href="{{ route('library.index') }}" class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-3 hover:bg-cyan-100">
                        📚 Library
                    </a>
                    <a href="{{ route('prayer-requests.wall') }}" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 hover:bg-rose-100">
                        🙏 Prayer wall
                    </a>
                    <a href="{{ route('testimonies.index') }}" class="rounded-xl border border-violet-200 bg-violet-50 px-4 py-3 hover:bg-violet-100">
                        🌈 Testimonies
                    </a>
                </div>
            </div>
        </div>

        <div class="app-panel border-emerald-800 bg-emerald-900 p-5 text-white sm:p-6">
            <p class="inline-flex items-center gap-2 rounded-full border border-emerald-300 bg-emerald-800 px-3 py-1 text-sm font-bold text-emerald-50"><x-ui.icon name="star" class="h-4 w-4" /> Featured reading 🌟</p>
            @if ($featuredDevotional)
                <h2 class="mt-4 text-2xl font-black tracking-normal">{{ $featuredDevotional->title }}</h2>
                <p class="mt-3 text-sm leading-6 text-emerald-50">{{ \Illuminate\Support\Str::limit(strip_tags($featuredDevotional->content), 180) }}</p>
                <a href="{{ route('devotionals.show', $featuredDevotional->slug) }}" class="mt-5 inline-flex min-h-11 items-center justify-center gap-2 rounded-full bg-white px-4 py-2.5 text-sm font-bold text-emerald-900 shadow-sm hover:bg-emerald-50">
                    Open devotional <x-ui.icon name="chevron-right" class="h-4 w-4" />
                </a>
            @else
                <h2 class="mt-4 text-2xl font-black tracking-normal">No featured devotional yet</h2>
                <p class="mt-3 text-sm leading-6 text-emerald-50">Publish a devotional from the admin dashboard to feature it here.</p>
            @endif
        </div>
    </section>

    <section>
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="app-eyebrow border-amber-200 bg-amber-50 text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> ✨ New readings</p>
                <h2 class="mt-3 app-section-title">Latest devotionals</h2>
                <p class="mt-1 text-sm text-slate-600">Fresh readings for reflection and prayer.</p>
            </div>
            <a href="{{ route('devotionals.index') }}" class="btn-secondary w-full sm:w-auto">View all <x-ui.icon name="chevron-right" class="h-4 w-4" /></a>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            @forelse ($latestDevotionals as $devotional)
                <article class="app-panel border-t-4 odd:border-t-amber-400 even:border-t-sky-400">
                    <p class="inline-flex items-center gap-2 rounded-full bg-olive-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-olive-800">🌿 {{ $devotional->category?->name }}</p>
                    <h3 class="mt-3 text-lg font-black tracking-normal text-slate-950">{{ $devotional->title }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($devotional->content), 135) }}</p>
                    <a href="{{ route('devotionals.show', $devotional->slug) }}" class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-emerald-800 hover:text-emerald-950">
                        Read <x-ui.icon name="chevron-right" class="h-4 w-4" />
                    </a>
                </article>
            @empty
                <div class="app-panel border-dashed border-slate-300 text-sm text-slate-600 md:col-span-3">No published devotionals yet.</div>
            @endforelse
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <div>
            <p class="app-eyebrow border-lime-200 bg-lime-50 text-lime-900"><x-ui.icon name="bookmark" class="h-4 w-4" /> 🌱 Browse topics</p>
            <h2 class="mt-3 app-section-title">Topics</h2>
            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                @forelse ($categories as $category)
                    <a href="{{ route('devotionals.index', ['category' => $category->slug]) }}" class="app-surface flex items-center justify-between gap-3 border-lime-100 p-4 hover:border-lime-300 hover:bg-lime-50">
                        <span>
                            <span class="block font-black tracking-normal text-slate-950">{{ $category->name }}</span>
                            <span class="mt-1 block text-sm text-slate-600">{{ $category->devotionals_count }} readings</span>
                        </span>
                        <span class="rounded-full bg-lime-100 px-2 py-1 text-sm">🌿</span>
                    </a>
                @empty
                    <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600">No active categories yet.</p>
                @endforelse
            </div>
        </div>

        <div>
            <p class="app-eyebrow border-fuchsia-200 bg-fuchsia-50 text-fuchsia-900"><x-ui.icon name="message-circle" class="h-4 w-4" /> 🌈 Shared praise</p>
            <h2 class="mt-3 app-section-title">Recent testimonies</h2>
            <div class="mt-4 space-y-3">
                @forelse ($testimonies as $testimony)
                    <article class="app-surface border-fuchsia-100 p-4 hover:border-fuchsia-300">
                        <h3 class="font-black tracking-normal text-slate-950">🌟 {{ $testimony->title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($testimony->body, 145) }}</p>
                        <p class="mt-3 text-xs font-bold text-fuchsia-800">{{ $testimony->is_anonymous ? 'Anonymous' : ($testimony->name ?: 'MannaRise reader') }}</p>
                    </article>
                @empty
                    <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600">Approved testimonies will appear here.</p>
                @endforelse
            </div>
        </div>
    </section>
</div>
