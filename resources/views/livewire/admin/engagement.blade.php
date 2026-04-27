<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-stone-950">Engagement summary</h1>
            <p class="mt-2 text-sm text-stone-600">User activity, completion counts, and top devotionals.</p>
        </div>
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900">
            {{ $completionCountThisWeek }} completions this week
        </div>
    </div>

    <section class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-semibold text-stone-950">Top devotionals</h2>
        <div class="mt-4 grid gap-3 md:grid-cols-2">
            @forelse ($topDevotionals as $devotional)
                <div class="rounded-md border border-stone-200 p-3">
                    <p class="font-semibold text-stone-950">{{ $devotional->title }}</p>
                    <p class="mt-1 text-sm text-stone-500">{{ $devotional->category?->name }} · {{ $devotional->completions_count }} completions · {{ $devotional->favorites_count }} favorites</p>
                </div>
            @empty
                <p class="text-sm text-stone-600">Engagement will appear after readers begin using devotionals.</p>
            @endforelse
        </div>
    </section>

    <section class="overflow-hidden rounded-lg border border-stone-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-stone-600">
                <tr>
                    <th class="px-4 py-3 font-semibold">User</th>
                    <th class="px-4 py-3 font-semibold">Favorites</th>
                    <th class="px-4 py-3 font-semibold">Journal</th>
                    <th class="px-4 py-3 font-semibold">Prayers</th>
                    <th class="px-4 py-3 font-semibold">Testimonies</th>
                    <th class="px-4 py-3 font-semibold">Completions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-stone-950">{{ $user->name }}</p>
                            <p class="text-stone-500">{{ $user->email }}</p>
                        </td>
                        <td class="px-4 py-3 text-stone-700">{{ $user->favorites_count }}</td>
                        <td class="px-4 py-3 text-stone-700">{{ $user->journal_entries_count }}</td>
                        <td class="px-4 py-3 text-stone-700">{{ $user->prayer_requests_count }}</td>
                        <td class="px-4 py-3 text-stone-700">{{ $user->testimonies_count }}</td>
                        <td class="px-4 py-3 text-stone-700">{{ $user->completions_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-stone-600">No users yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    {{ $users->links() }}
</div>
