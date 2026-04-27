<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-semibold text-stone-950">Admin dashboard</h1>
        <p class="mt-2 text-sm text-stone-600">Content, moderation, and engagement at a glance.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($stats as $label => $value)
            <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm capitalize text-stone-600">{{ str_replace('_', ' ', $label) }}</p>
                <p class="mt-2 text-3xl font-semibold text-stone-950">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-xl font-semibold text-stone-950">Recent devotionals</h2>
                <a href="{{ route('admin.devotionals') }}" class="text-sm font-semibold text-emerald-800 hover:underline">Manage</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($recentDevotionals as $devotional)
                    <div class="rounded-md border border-stone-200 p-3">
                        <p class="font-semibold text-stone-950">{{ $devotional->title }}</p>
                        <p class="mt-1 text-sm text-stone-500">{{ $devotional->category?->name }} · {{ $devotional->is_published ? 'Published' : 'Draft' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-stone-600">No devotionals yet.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-xl font-semibold text-stone-950">Recent prayer requests</h2>
                <a href="{{ route('admin.prayer-requests') }}" class="text-sm font-semibold text-emerald-800 hover:underline">Manage</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($recentPrayerRequests as $request)
                    <div class="rounded-md border border-stone-200 p-3">
                        <p class="font-semibold text-stone-950">{{ $request->title }}</p>
                        <p class="mt-1 text-sm text-stone-500">{{ $request->name ?: 'Anonymous' }} · {{ $request->is_answered ? 'Answered' : 'Open' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-stone-600">No prayer requests yet.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
