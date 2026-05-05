<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-cyan-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-cyan-500"></span>
            <span class="bg-blue-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-emerald-500"></span>
        </div>
        <div class="flex flex-col gap-4 p-5 sm:p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-cyan-200 bg-cyan-50 text-cyan-900"><x-ui.icon name="mail" class="h-4 w-4" /> Mail delivery</p>
                <h1 class="mt-3 app-section-title">Scheduled mail center</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Review sent reminder emails, see scheduled mail that is due, and manually send pending reminders without bypassing user opt-out preferences.</p>
            </div>
            <a href="{{ route('admin.engagement') }}" class="btn-secondary border-cyan-200">Engagement logs</a>
        </div>
    </div>

    @if (session('status'))
        <div class="app-panel border-emerald-200 bg-emerald-50 text-sm font-bold text-emerald-900">{{ session('status') }}</div>
    @endif

    @if (session('error'))
        <div class="app-panel border-red-200 bg-red-50 text-sm font-bold text-red-900">{{ session('error') }}</div>
    @endif

    <section class="grid gap-4 sm:grid-cols-3">
        <div class="metric-card border-emerald-200 bg-emerald-50 text-emerald-900">
            <p class="flex items-center gap-2 text-sm font-bold"><x-ui.icon name="check-circle" class="h-4 w-4" /> Mail enabled</p>
            <p class="mt-2 text-3xl font-black tracking-normal text-slate-950">{{ $mailEnabledCount }}</p>
        </div>
        <div class="metric-card border-amber-200 bg-amber-50 text-amber-900">
            <p class="flex items-center gap-2 text-sm font-bold"><x-ui.icon name="bell" class="h-4 w-4" /> Opted out</p>
            <p class="mt-2 text-3xl font-black tracking-normal text-slate-950">{{ $optedOutCount }}</p>
        </div>
        <div class="metric-card border-red-200 bg-red-50 text-red-900">
            <p class="flex items-center gap-2 text-sm font-bold"><x-ui.icon name="refresh-cw" class="h-4 w-4" /> Failed mail</p>
            <p class="mt-2 text-3xl font-black tracking-normal text-slate-950">{{ $failedCount }}</p>
        </div>
    </section>

    <section class="grid gap-5 lg:grid-cols-2">
        <article class="app-panel border-emerald-200 bg-emerald-50">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="bell" class="h-5 w-5 text-emerald-800" /> Scheduled daily reminder mail</h2>
            <div class="mt-4 space-y-3">
                @forelse ($scheduledDaily as $reminder)
                    <div class="rounded-xl border border-white bg-white p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="font-black tracking-normal text-slate-950">{{ $reminder->user?->name }}</p>
                                <p class="text-sm text-slate-500">{{ $reminder->user?->email }}</p>
                                <p class="mt-1 text-sm font-bold text-emerald-900">{{ substr($reminder->remind_at, 0, 5) }} · {{ $reminder->timezone }}</p>
                            </div>
                            <button type="button" wire:click="sendDaily({{ $reminder->id }})" class="btn-primary bg-emerald-700 hover:bg-emerald-800">
                                <x-ui.icon name="send" class="h-4 w-4" /> Send now
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="rounded-xl border border-dashed border-emerald-200 bg-white p-4 text-sm text-slate-600">No unsent daily reminder emails are scheduled right now.</p>
                @endforelse
            </div>
        </article>

        <article class="app-panel border-violet-200 bg-violet-50">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="bar-chart" class="h-5 w-5 text-violet-800" /> Scheduled weekly digest mail</h2>
            <div class="mt-4 space-y-3">
                @forelse ($scheduledWeekly as $reminder)
                    <div class="rounded-xl border border-white bg-white p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="font-black tracking-normal text-slate-950">{{ $reminder->user?->name }}</p>
                                <p class="text-sm text-slate-500">{{ $reminder->user?->email }}</p>
                                <p class="mt-1 text-sm font-bold text-violet-900">Weekly digest enabled</p>
                            </div>
                            <button type="button" wire:click="sendWeeklyDigest({{ $reminder->id }})" class="btn-primary bg-violet-700 hover:bg-violet-800">
                                <x-ui.icon name="send" class="h-4 w-4" /> Send digest
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="rounded-xl border border-dashed border-violet-200 bg-white p-4 text-sm text-slate-600">No weekly digest emails are scheduled right now.</p>
                @endforelse
            </div>
        </article>
    </section>

    <section class="app-panel border-cyan-200">
        <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="mail" class="h-5 w-5 text-cyan-800" /> Sent and failed mail</h2>
        <div class="mt-4 overflow-x-auto rounded-xl border border-cyan-200 bg-white">
            <table class="min-w-full divide-y divide-cyan-100 text-sm">
                <thead class="bg-cyan-50 text-left text-cyan-900">
                    <tr>
                        <th class="px-4 py-3 font-black">When</th>
                        <th class="px-4 py-3 font-black">User</th>
                        <th class="px-4 py-3 font-black">Type</th>
                        <th class="px-4 py-3 font-black">Status</th>
                        <th class="px-4 py-3 font-black">Subject</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-100">
                    @forelse ($sentLogs as $log)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-3 font-bold text-slate-600">{{ $log->created_at->format('M j, g:i A') }}</td>
                            <td class="px-4 py-3">
                                <p class="font-black tracking-normal text-slate-950">{{ $log->user?->name ?? 'Deleted user' }}</p>
                                <p class="text-slate-500">{{ $log->user?->email }}</p>
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-700">{{ str($log->notification_type)->headline() }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-1 text-xs font-black {{ $log->status === 'sent' ? 'bg-emerald-100 text-emerald-900' : 'bg-red-100 text-red-900' }}">{{ str($log->status)->headline() }}</span>
                            </td>
                            <td class="max-w-lg px-4 py-3 text-slate-600">
                                <p class="font-bold text-slate-800">{{ $log->subject }}</p>
                                <p class="mt-1">{{ \Illuminate\Support\Str::limit($log->error_message ?: $log->message, 120) }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-slate-600">No mail delivery records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $sentLogs->links() }}
        </div>
    </section>
</div>
