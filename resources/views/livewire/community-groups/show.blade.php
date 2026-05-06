<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-indigo-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-indigo-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-lime-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-rose-500"></span>
        </div>
        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,30rem)] lg:items-end">
            <div>
                <a href="{{ route('community-groups.index') }}" class="mb-4 inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-slate-950"><x-ui.icon name="chevron-left" class="h-4 w-4" /> Groups</a>
                <p class="app-eyebrow border-indigo-200 bg-indigo-50 text-indigo-900"><x-ui.icon name="users" class="h-4 w-4" /> {{ $group->typeLabel() }}</p>
                <h1 class="mt-3 app-section-title">{{ $group->name }}</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">{{ $group->description ?: 'Shared Bible reading, prayer, and encouragement for this community.' }}</p>
            </div>

            <div class="grid gap-3">
                @if ($membership)
                    <div class="app-surface grid gap-3 p-4 sm:grid-cols-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-normal text-slate-500">Streak</p>
                            <p class="mt-1 text-2xl font-black tracking-normal text-slate-950">{{ $membership->current_reading_streak }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-normal text-slate-500">Best</p>
                            <p class="mt-1 text-2xl font-black tracking-normal text-slate-950">{{ $membership->longest_reading_streak }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-normal text-slate-500">Chapters</p>
                            <p class="mt-1 text-2xl font-black tracking-normal text-slate-950">{{ $membership->completed_chapters_count }}</p>
                        </div>
                    </div>
                @elseif ($group->visibility === 'public')
                    <button type="button" wire:click="join" class="btn-primary w-full bg-indigo-700 hover:bg-indigo-800"><x-ui.icon name="users" class="h-4 w-4" /> Join group</button>
                @endif

                <div class="app-surface grid grid-cols-3 gap-3 border-indigo-100 bg-indigo-50 p-4 text-center">
                    <div>
                        <p class="text-2xl font-black tracking-normal text-slate-950">{{ $group->memberships->count() }}</p>
                        <p class="text-xs font-bold uppercase tracking-normal text-indigo-900">Members</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black tracking-normal text-slate-950">{{ $todayReadingCount }}</p>
                        <p class="text-xs font-bold uppercase tracking-normal text-indigo-900">Today</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black tracking-normal text-slate-950">{{ $activeChallenges->count() }}</p>
                        <p class="text-xs font-bold uppercase tracking-normal text-indigo-900">Active</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($membership)
        <section class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(20rem,0.8fr)]">
            <article class="app-panel border-cyan-200 bg-cyan-50">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="app-eyebrow border-cyan-200 bg-white text-cyan-900"><x-ui.icon name="bar-chart" class="h-4 w-4" /> Weekly reflection</p>
                        <h2 class="mt-3 app-section-title">This group&apos;s rhythm</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-700">{{ $weeklySummary['summary'] }}</p>
                    </div>
                    <div class="rounded-xl border border-white bg-white p-4 text-center">
                        <p class="text-3xl font-black tracking-normal text-slate-950">{{ $weeklySummary['prayer_count'] }}/{{ $weeklySummary['prayer_goal'] }}</p>
                        <p class="text-xs font-bold uppercase tracking-normal text-cyan-900">Prayer goal</p>
                    </div>
                </div>
                <div class="mt-4 h-3 overflow-hidden rounded-full bg-white">
                    <div class="h-full rounded-full bg-cyan-700" style="width: {{ $weeklySummary['prayer_percent'] }}%"></div>
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl bg-white p-3">
                        <p class="text-2xl font-black text-slate-950">{{ $weeklySummary['reading_count'] }}</p>
                        <p class="text-xs font-bold uppercase tracking-normal text-cyan-900">Chapters</p>
                    </div>
                    <div class="rounded-xl bg-white p-3">
                        <p class="text-2xl font-black text-slate-950">{{ $weeklySummary['new_prayers'] }}</p>
                        <p class="text-xs font-bold uppercase tracking-normal text-cyan-900">New prayers</p>
                    </div>
                    <div class="rounded-xl bg-white p-3">
                        <p class="text-2xl font-black text-slate-950">{{ $weeklySummary['answered_prayers'] }}</p>
                        <p class="text-xs font-bold uppercase tracking-normal text-cyan-900">Answered</p>
                    </div>
                </div>
            </article>

            <aside class="app-panel border-indigo-200">
                <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="message-circle" class="h-5 w-5 text-indigo-800" /> Discussion prompts</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($discussionPrompts as $prompt)
                        <article class="rounded-xl border border-indigo-100 bg-indigo-50 p-4">
                            <p class="text-xs font-black uppercase tracking-normal text-indigo-900">Week of {{ $prompt->week_start->format('M j') }}</p>
                            <h3 class="mt-2 font-black tracking-normal text-slate-950">{{ $prompt->title ?: 'Group reflection' }}</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-700">{{ $prompt->prompt }}</p>
                        </article>
                    @empty
                        <p class="rounded-xl border border-dashed border-indigo-200 bg-indigo-50 p-4 text-sm text-slate-600">Leaders can add weekly prompts for group reflection.</p>
                    @endforelse
                </div>
            </aside>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.05fr)_minmax(22rem,0.95fr)]">
            <div class="app-panel border-emerald-200 bg-emerald-50">
                <p class="app-eyebrow border-emerald-200 bg-white text-emerald-900"><x-ui.icon name="book-open" class="h-4 w-4" /> Group challenge</p>
                <h2 class="mt-3 text-2xl font-black tracking-normal text-slate-950">Log today&apos;s reading</h2>

                <form wire:submit="logReading" class="mt-5 space-y-4">
                    <div class="grid gap-3 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Challenge</label>
                            <select wire:model="selectedChallengeId" class="field-input mt-1 border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100">
                                @foreach ($activeChallenges as $challenge)
                                    <option value="{{ $challenge->id }}">{{ $challenge->title }}</option>
                                @endforeach
                            </select>
                            @error('selectedChallengeId') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Book</label>
                            <select wire:model="readingBookId" class="field-input mt-1 border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100">
                                <option value="">Choose book</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->name }}</option>
                                @endforeach
                            </select>
                            @error('readingBookId') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-3 md:grid-cols-[10rem_1fr]">
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Chapter</label>
                            <input type="number" min="1" wire:model="readingChapter" class="field-input mt-1 border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100">
                            @error('readingChapter') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Note</label>
                            <input type="text" wire:model="readingNotes" class="field-input mt-1 border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100">
                            @error('readingNotes') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn-primary"><x-ui.icon name="check-circle" class="h-4 w-4" /> Log reading</button>
                </form>
            </div>

            <section class="app-panel border-amber-200">
                <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="star" class="h-5 w-5 text-amber-800" /> Reading leaderboard</h2>
                <div class="mt-4 space-y-2">
                    @forelse ($leaderboard as $index => $row)
                        <div class="flex items-center justify-between gap-3 rounded-xl border border-amber-100 bg-amber-50 px-3 py-3 text-sm">
                            <span class="flex min-w-0 items-center gap-3">
                                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white font-black text-amber-900">{{ $index + 1 }}</span>
                                <span class="min-w-0">
                                    <span class="block truncate font-black tracking-normal text-slate-950">{{ $row->user?->name ?? 'Member' }}</span>
                                    <span class="block font-bold text-slate-500">{{ $row->completed_chapters_count }} chapters</span>
                                </span>
                            </span>
                            <span class="rounded-full bg-white px-3 py-1 font-black text-amber-900">{{ $row->current_reading_streak }} day streak</span>
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-amber-200 bg-amber-50 p-4 text-sm text-slate-600">Leaderboard starts when members log readings.</p>
                    @endforelse
                </div>
            </section>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
            <div class="app-panel border-rose-200 bg-rose-50">
                <p class="app-eyebrow border-rose-200 bg-white text-rose-900"><x-ui.icon name="heart" class="h-4 w-4" /> Private prayer circle</p>
                <h2 class="mt-3 text-2xl font-black tracking-normal text-slate-950">Share a prayer</h2>
                <form wire:submit="createPrayer" class="mt-5 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Title</label>
                        <input type="text" wire:model="prayerTitle" class="field-input mt-1 border-rose-300 focus:border-rose-600 focus:ring-rose-100">
                        @error('prayerTitle') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Prayer request</label>
                        <textarea wire:model="prayerBody" rows="5" class="field-input mt-1 border-rose-300 focus:border-rose-600 focus:ring-rose-100"></textarea>
                        @error('prayerBody') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="btn-primary bg-rose-700 hover:bg-rose-800"><x-ui.icon name="send" class="h-4 w-4" /> Share privately</button>
                </form>
            </div>

            <div class="space-y-3">
                @forelse ($prayers as $prayer)
                    <article class="app-panel border-t-4 {{ $prayer->is_answered ? 'border-t-emerald-500' : 'border-t-rose-500' }}">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-normal {{ $prayer->is_answered ? 'bg-emerald-50 text-emerald-900' : 'bg-rose-50 text-rose-900' }}">{{ $prayer->is_answered ? 'Answered' : 'Private' }}</span>
                            <span class="text-xs font-bold text-slate-500">{{ $prayer->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="mt-3 font-black tracking-normal text-slate-950">{{ $prayer->title }}</h3>
                        <p class="mt-1 text-sm font-bold text-slate-500">{{ $prayer->user?->name ?? 'Member' }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-700">{{ $prayer->body }}</p>
                        <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                            <span class="text-sm font-bold text-rose-800">{{ $prayer->prayed_count }} prayed</span>
                            <div class="flex flex-wrap gap-2">
                                @if ($canManage || $prayer->user_id === auth()->id())
                                    <button type="button" wire:click="markPrayerAnswered({{ $prayer->id }})" class="btn-secondary border-emerald-200 px-3 hover:bg-emerald-50">{{ $prayer->is_answered ? 'Reopen' : 'Answered' }}</button>
                                @endif
                                <button type="button" wire:click="pray({{ $prayer->id }})" class="btn-primary bg-rose-700 px-3 hover:bg-rose-800"><x-ui.icon name="heart" class="h-4 w-4" /> I prayed</button>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600">No private prayers in this group yet.</p>
                @endforelse
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]">
            <section class="app-panel border-sky-200">
                <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="book-open" class="h-5 w-5 text-sky-800" /> Recent reading</h2>
                <div class="mt-4 space-y-2">
                    @forelse ($recentReadingLogs as $log)
                        <div class="rounded-xl border border-sky-100 bg-sky-50 p-3 text-sm">
                            <p class="font-black tracking-normal text-slate-950">{{ $log->user?->name ?? 'Member' }} read {{ $log->book?->name }} {{ $log->chapter }}</p>
                            <p class="mt-1 font-bold text-slate-500">{{ $log->challenge?->title ?? 'Group reading' }} &middot; {{ $log->read_on->format('M j, Y') }}</p>
                            @if ($log->notes)
                                <p class="mt-2 leading-6 text-slate-700">{{ $log->notes }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-sky-200 bg-sky-50 p-4 text-sm text-slate-600">No readings logged yet.</p>
                    @endforelse
                </div>
            </section>

            @if ($canManage)
                <section class="app-panel border-violet-200">
                    <p class="app-eyebrow border-violet-200 bg-violet-50 text-violet-900"><x-ui.icon name="calendar" class="h-4 w-4" /> Leader tools</p>
                    <h2 class="mt-3 text-xl font-black tracking-normal text-slate-950">Challenge and invite links</h2>

                    <form wire:submit="createChallenge" class="mt-5 space-y-4 rounded-xl border border-violet-100 bg-violet-50 p-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Challenge title</label>
                            <input type="text" wire:model="challengeTitle" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                            @error('challengeTitle') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid gap-3 sm:grid-cols-3">
                            <div>
                                <label class="block text-sm font-bold text-slate-700">Starts</label>
                                <input type="date" wire:model="challengeStartsOn" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700">Ends</label>
                                <input type="date" wire:model="challengeEndsOn" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700">Daily goal</label>
                                <input type="number" min="1" max="12" wire:model="dailyChapterGoal" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Description</label>
                            <textarea wire:model="challengeDescription" rows="3" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100"></textarea>
                        </div>
                        <button type="submit" class="btn-primary bg-violet-700 hover:bg-violet-800">Create challenge</button>
                    </form>

                    <form wire:submit="saveGroupRhythm" class="mt-5 space-y-4 rounded-xl border border-cyan-100 bg-cyan-50 p-4">
                        <h3 class="font-black tracking-normal text-slate-950">Prayer goal and reminder</h3>
                        <div class="grid gap-3 sm:grid-cols-3">
                            <div>
                                <label class="block text-sm font-bold text-slate-700">Weekly prayer goal</label>
                                <input type="number" min="1" max="200" wire:model="weeklyPrayerGoal" class="field-input mt-1 border-cyan-300 focus:border-cyan-600 focus:ring-cyan-100">
                                @error('weeklyPrayerGoal') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700">Reminder day</label>
                                <select wire:model="reminderDay" class="field-input mt-1 border-cyan-300 focus:border-cyan-600 focus:ring-cyan-100">
                                    <option value="">No day set</option>
                                    @foreach (['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                                        <option value="{{ $day }}">{{ str($day)->headline() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700">Reminder time</label>
                                <input type="time" wire:model="reminderTime" class="field-input mt-1 border-cyan-300 focus:border-cyan-600 focus:ring-cyan-100">
                                @error('reminderTime') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn-secondary border-cyan-200 text-cyan-900 hover:bg-white"><x-ui.icon name="bell" class="h-4 w-4" /> Save rhythm</button>
                    </form>

                    <form wire:submit="createPrompt" class="mt-5 space-y-4 rounded-xl border border-amber-100 bg-amber-50 p-4">
                        <h3 class="font-black tracking-normal text-slate-950">Weekly discussion prompt</h3>
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Title</label>
                            <input type="text" wire:model="promptTitle" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                            @error('promptTitle') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Prompt</label>
                            <textarea wire:model="promptText" rows="3" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100"></textarea>
                            @error('promptText') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="btn-secondary border-amber-200 text-amber-900 hover:bg-white"><x-ui.icon name="message-circle" class="h-4 w-4" /> Add prompt</button>
                    </form>

                    <form wire:submit="createInvite" class="mt-5 space-y-4 rounded-xl border border-indigo-100 bg-indigo-50 p-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Invite label</label>
                            <input type="text" wire:model="inviteLabel" placeholder="Men's group, leaders, family" class="field-input mt-1 border-indigo-300 focus:border-indigo-600 focus:ring-indigo-100">
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-bold text-slate-700">Expires</label>
                                <input type="datetime-local" wire:model="inviteExpiresAt" class="field-input mt-1 border-indigo-300 focus:border-indigo-600 focus:ring-indigo-100">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700">Use limit</label>
                                <input type="number" min="1" wire:model="inviteMaxUses" class="field-input mt-1 border-indigo-300 focus:border-indigo-600 focus:ring-indigo-100">
                            </div>
                        </div>
                        <button type="submit" class="btn-secondary border-indigo-200 text-indigo-900 hover:bg-white"><x-ui.icon name="send" class="h-4 w-4" /> Create invite link</button>
                    </form>

                    <div class="mt-5 space-y-2">
                        @forelse ($invites as $invite)
                            <div class="rounded-xl border border-slate-200 bg-white p-3 text-sm">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <p class="font-black tracking-normal text-slate-950">{{ $invite->label ?: 'Group invite' }}</p>
                                    <button type="button" wire:click="toggleInvite({{ $invite->id }})" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-bold text-slate-700 hover:bg-slate-50">{{ $invite->is_active ? 'Disable' : 'Enable' }}</button>
                                </div>
                                <input type="text" readonly value="{{ route('community-groups.invite', $invite->token) }}" class="field-input mt-2 border-slate-200 bg-slate-50 text-xs">
                                <p class="mt-2 font-bold text-slate-500">{{ $invite->uses_count }} used @if ($invite->max_uses) of {{ $invite->max_uses }} @endif @if ($invite->expires_at) &middot; expires {{ $invite->expires_at->format('M j, Y') }} @endif</p>
                            </div>
                        @empty
                            <p class="rounded-xl border border-dashed border-indigo-200 bg-indigo-50 p-4 text-sm text-slate-600">No invite links created yet.</p>
                        @endforelse
                    </div>
                </section>
            @endif
        </section>
    @else
        <section class="app-panel border-dashed border-indigo-200 bg-indigo-50">
            <h2 class="text-xl font-black tracking-normal text-slate-950">Join to participate</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Members can log readings, join the leaderboard, and pray inside the private circle.</p>
            @if ($group->visibility === 'public')
                <button type="button" wire:click="join" class="mt-4 btn-primary bg-indigo-700 hover:bg-indigo-800"><x-ui.icon name="users" class="h-4 w-4" /> Join group</button>
            @endif
        </section>
    @endif
</div>
