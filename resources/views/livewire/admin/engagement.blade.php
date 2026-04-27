<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-blue-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-blue-500"></span>
            <span class="bg-indigo-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-purple-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-lime-500"></span>
        </div>
        <div class="flex flex-col gap-4 p-5 sm:p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-blue-200 bg-blue-50 text-blue-900"><x-ui.icon name="bar-chart" class="h-4 w-4" /> 📊 Engagement</p>
                <h1 class="mt-3 app-section-title">Engagement summary</h1>
                <p class="mt-2 text-sm text-slate-600">User activity, completion counts, and top devotionals.</p>
            </div>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-black text-emerald-900 shadow-sm">
                ✅ {{ $completionCountThisWeek }} completions this week
            </div>
        </div>
    </div>

    <section class="app-panel border-emerald-200">
        <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="sparkles" class="h-5 w-5 text-emerald-800" /> Top devotionals ✨</h2>
        <div class="mt-4 grid gap-3 md:grid-cols-2">
            @forelse ($topDevotionals as $devotional)
                <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                    <p class="font-black tracking-normal text-slate-950">{{ $devotional->title }}</p>
                    <p class="mt-1 text-sm font-bold text-slate-500">{{ $devotional->category?->name }} · {{ $devotional->completions_count }} completions · {{ $devotional->favorites_count }} favorites</p>
                </div>
            @empty
                <p class="rounded-xl border border-dashed border-emerald-200 bg-emerald-50 p-4 text-sm text-slate-600">Engagement will appear after readers begin using devotionals.</p>
            @endforelse
        </div>
    </section>

    <section class="overflow-x-auto rounded-xl border border-blue-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-blue-100 text-sm">
            <thead class="bg-blue-50 text-left text-blue-900">
                <tr>
                    <th class="px-4 py-3 font-black">User</th>
                    <th class="px-4 py-3 font-black">Favorites</th>
                    <th class="px-4 py-3 font-black">Journal</th>
                    <th class="px-4 py-3 font-black">Prayers</th>
                    <th class="px-4 py-3 font-black">Testimonies</th>
                    <th class="px-4 py-3 font-black">Completions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-100">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-black tracking-normal text-slate-950">{{ $user->name }}</p>
                            <p class="text-slate-500">{{ $user->email }}</p>
                        </td>
                        <td class="px-4 py-3 font-bold text-slate-700">{{ $user->favorites_count }}</td>
                        <td class="px-4 py-3 font-bold text-slate-700">{{ $user->journal_entries_count }}</td>
                        <td class="px-4 py-3 font-bold text-slate-700">{{ $user->prayer_requests_count }}</td>
                        <td class="px-4 py-3 font-bold text-slate-700">{{ $user->testimonies_count }}</td>
                        <td class="px-4 py-3 font-bold text-slate-700">{{ $user->completions_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-slate-600">No users yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    {{ $users->links() }}
</div>
