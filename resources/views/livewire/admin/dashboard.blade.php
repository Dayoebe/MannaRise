<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-indigo-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-indigo-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-purple-500"></span>
            <span class="bg-fuchsia-500"></span>
            <span class="bg-cyan-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow border-indigo-200 bg-indigo-50 text-indigo-900"><x-ui.icon name="shield" class="h-4 w-4" /> Admin</p>
            <h1 class="mt-3 app-section-title">Admin dashboard</h1>
            <p class="mt-2 text-sm text-slate-600">Content, moderation, and engagement at a glance.</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($stats as $label => $value)
            <div class="metric-card border-indigo-200 bg-indigo-50">
                <p class="flex items-center gap-2 text-sm font-bold capitalize text-indigo-900"><x-ui.icon name="bar-chart" class="h-4 w-4" /> {{ str_replace('_', ' ', $label) }}</p>
                <p class="mt-2 text-3xl font-black tracking-normal text-slate-950">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="app-panel border-emerald-200">
            <div class="flex items-center justify-between gap-3">
                <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="sparkles" class="h-5 w-5 text-emerald-800" /> Recent devotionals</h2>
                <a href="{{ route('admin.devotionals') }}" class="text-sm font-bold text-emerald-800 hover:underline">Manage</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($recentDevotionals as $devotional)
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                        <p class="font-black tracking-normal text-slate-950">{{ $devotional->title }}</p>
                        <p class="mt-1 text-sm font-bold text-slate-500">{{ $devotional->category?->name }} · {{ $devotional->is_published ? 'Published' : 'Draft' }}</p>
                    </div>
                @empty
                    <p class="rounded-xl border border-dashed border-emerald-200 bg-emerald-50 p-4 text-sm text-slate-600">No devotionals yet.</p>
                @endforelse
            </div>
        </section>

        <section class="app-panel border-rose-200">
            <div class="flex items-center justify-between gap-3">
                <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="heart" class="h-5 w-5 text-rose-800" /> Recent prayer requests</h2>
                <a href="{{ route('admin.prayer-requests') }}" class="text-sm font-bold text-rose-800 hover:underline">Manage</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($recentPrayerRequests as $request)
                    <div class="rounded-xl border border-rose-100 bg-rose-50 p-4">
                        <p class="font-black tracking-normal text-slate-950">{{ $request->title }}</p>
                        <p class="mt-1 text-sm font-bold text-slate-500">{{ $request->name ?: 'Anonymous' }} · {{ $request->is_answered ? 'Answered' : 'Open' }}</p>
                    </div>
                @empty
                    <p class="rounded-xl border border-dashed border-rose-200 bg-rose-50 p-4 text-sm text-slate-600">No prayer requests yet.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
