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
                <p class="app-eyebrow border-blue-200 bg-blue-50 text-blue-900"><x-ui.icon name="bar-chart" class="h-4 w-4" /> Engagement</p>
                <h1 class="mt-3 app-section-title">Engagement summary</h1>
                <p class="mt-2 text-sm text-slate-600">User activity, completion counts, and top devotionals.</p>
            </div>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-black text-emerald-900 shadow-sm">
                 {{ $completionCountThisWeek }} completions this week
            </div>
        </div>
    </div>

    <section class="app-panel border-amber-200 bg-amber-50">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Content planning intelligence</p>
                <h2 class="mt-3 app-section-title">What to plan next</h2>
                <p class="mt-2 text-sm leading-6 text-slate-700">Suggestions are based on recent devotionals, prayer rooms, journal topics, prayer requests, and marked Bible verses.</p>
            </div>
            <a href="{{ route('admin.featured-content') }}" class="btn-secondary border-amber-200 bg-white text-amber-900 hover:bg-amber-50">Featured controls</a>
        </div>

        <div class="mt-5 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($contentSuggestions as $suggestion)
                <article class="rounded-xl border border-white bg-white p-4 shadow-sm">
                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-amber-900">{{ $suggestion['type'] }}</span>
                    <h3 class="mt-3 font-black tracking-normal text-slate-950">{{ $suggestion['title'] }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $suggestion['detail'] }}</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <button type="button" wire:click="createDraftFromSuggestion(@js($suggestion['title']), @js($suggestion['detail']))" class="btn-primary bg-amber-600 px-3 text-slate-950 hover:bg-amber-500">
                            <x-ui.icon name="journal" class="h-4 w-4" /> Create draft
                        </button>
                        <a href="{{ route('admin.featured-content') }}" class="btn-secondary border-amber-200 px-3">{{ $suggestion['action'] }}</a>
                    </div>
                </article>
            @empty
                <p class="rounded-xl border border-dashed border-amber-200 bg-white p-4 text-sm text-slate-600">Planning suggestions will appear as users create prayer, journal, and Bible-reading signals.</p>
            @endforelse
        </div>
    </section>

    <section class="app-panel border-emerald-200">
        <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="sparkles" class="h-5 w-5 text-emerald-800" /> Top devotionals</h2>
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

    <section class="app-panel border-cyan-200 bg-cyan-50">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="app-eyebrow border-cyan-200 bg-white text-cyan-900"><x-ui.icon name="bell" class="h-4 w-4" /> Notification delivery</p>
                <h2 class="mt-3 app-section-title">Reminder and digest logs</h2>
                <p class="mt-2 text-sm leading-6 text-slate-700">Recent email and in-app delivery records for daily reminders, missed-day nudges, and weekly digests.</p>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center sm:grid-cols-4">
                @foreach ([
                    ['Sent', $deliveryStats['sent'], 'text-emerald-900'],
                    ['Failed', $deliveryStats['failed'], 'text-red-900'],
                    ['Email', $deliveryStats['email'], 'text-blue-900'],
                    ['Push ready', $deliveryStats['push_ready'], 'text-violet-900'],
                ] as [$label, $value, $color])
                    <div class="rounded-xl border border-white bg-white px-3 py-2">
                        <p class="text-xl font-black tracking-normal text-slate-950">{{ $value }}</p>
                        <p class="text-xs font-bold uppercase tracking-normal {{ $color }}">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-5 overflow-x-auto rounded-xl border border-cyan-200 bg-white">
            <table class="min-w-full divide-y divide-cyan-100 text-sm">
                <thead class="bg-cyan-50 text-left text-cyan-900">
                    <tr>
                        <th class="px-4 py-3 font-black">When</th>
                        <th class="px-4 py-3 font-black">User</th>
                        <th class="px-4 py-3 font-black">Type</th>
                        <th class="px-4 py-3 font-black">Channel</th>
                        <th class="px-4 py-3 font-black">Status</th>
                        <th class="px-4 py-3 font-black">Message</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-100">
                    @forelse ($deliveryLogs as $log)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-3 font-bold text-slate-600">{{ $log->created_at->format('M j, g:i A') }}</td>
                            <td class="px-4 py-3">
                                <p class="font-black tracking-normal text-slate-950">{{ $log->user?->name ?? 'Deleted user' }}</p>
                                <p class="text-slate-500">{{ $log->user?->email }}</p>
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-700">{{ str($log->notification_type)->headline() }}</td>
                            <td class="px-4 py-3 font-bold text-slate-700">{{ $log->channel === 'database' ? 'In-app' : str($log->channel)->headline() }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-1 text-xs font-black {{ $log->status === 'sent' ? 'bg-emerald-100 text-emerald-900' : 'bg-red-100 text-red-900' }}">{{ str($log->status)->headline() }}</span>
                            </td>
                            <td class="max-w-md px-4 py-3 text-slate-600">
                                <p class="font-bold text-slate-800">{{ $log->subject }}</p>
                                <p class="mt-1">{{ \Illuminate\Support\Str::limit($log->error_message ?: $log->message, 120) }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-6 text-slate-600">No reminder or digest delivery logs yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
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
