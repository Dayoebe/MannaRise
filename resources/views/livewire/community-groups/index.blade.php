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
        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,26rem)] lg:items-end">
            <div>
                <p class="app-eyebrow border-indigo-200 bg-indigo-50 text-indigo-900"><x-ui.icon name="users" class="h-4 w-4" /> Community</p>
                <h1 class="mt-3 app-section-title">Church and ministry groups</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Gather your small group around Bible reading consistency, private prayer, and shared encouragement.</p>
            </div>
            <div class="app-surface grid grid-cols-3 gap-3 border-indigo-100 bg-indigo-50 p-4 text-center">
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">{{ $groups->count() }}</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-indigo-900">Mine</p>
                </div>
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">{{ $groups->sum('memberships_count') }}</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-indigo-900">Members</p>
                </div>
                <div>
                    <p class="text-2xl font-black tracking-normal text-slate-950">{{ $groups->sum('reading_challenges_count') }}</p>
                    <p class="text-xs font-bold uppercase tracking-normal text-indigo-900">Challenges</p>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]">
        <form wire:submit="createGroup" class="app-panel border-emerald-200 bg-emerald-50">
            <p class="app-eyebrow border-emerald-200 bg-white text-emerald-900"><x-ui.icon name="users" class="h-4 w-4" /> New group</p>
            <h2 class="mt-3 text-2xl font-black tracking-normal text-slate-950">Create a ministry group</h2>

            <div class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700">Group name</label>
                    <input type="text" wire:model="name" class="field-input mt-1 border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100">
                    @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Type</label>
                        <select wire:model="ministry_type" class="field-input mt-1 border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100">
                            <option value="small_group">Small group</option>
                            <option value="church">Church</option>
                            <option value="ministry">Ministry</option>
                            <option value="youth">Youth group</option>
                        </select>
                        @error('ministry_type') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Visibility</label>
                        <select wire:model="visibility" class="field-input mt-1 border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100">
                            <option value="private">Private</option>
                            <option value="public">Public discovery</option>
                        </select>
                        @error('visibility') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700">Description</label>
                    <textarea wire:model="description" rows="4" class="field-input mt-1 border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100"></textarea>
                    @error('description') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit" class="mt-5 btn-primary"><x-ui.icon name="users" class="h-4 w-4" /> Create group</button>
        </form>

        <section>
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="text-2xl font-black tracking-normal text-slate-950">My groups</h2>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                @forelse ($groups as $group)
                    <article class="app-panel public-card border-t-4 border-t-indigo-500 border-indigo-200">
                        <div class="flex items-start justify-between gap-3">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-900">
                                <x-ui.icon name="users" class="h-5 w-5" />
                            </span>
                            <span class="rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-indigo-900">{{ $group->typeLabel() }}</span>
                        </div>
                        <h3 class="mt-4 text-xl font-black tracking-normal text-slate-950">{{ $group->name }}</h3>
                        <p class="mt-2 flex-1 text-sm leading-6 text-slate-600">{{ $group->description ?: 'A MannaRise community group.' }}</p>
                        <div class="mt-5 grid grid-cols-3 gap-2 text-center">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-2 py-3">
                                <p class="text-lg font-black tracking-normal text-slate-950">{{ $group->memberships_count }}</p>
                                <p class="text-xs font-bold text-slate-500">members</p>
                            </div>
                            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-2 py-3">
                                <p class="text-lg font-black tracking-normal text-slate-950">{{ $group->reading_challenges_count }}</p>
                                <p class="text-xs font-bold text-emerald-900">reading</p>
                            </div>
                            <div class="rounded-xl border border-rose-200 bg-rose-50 px-2 py-3">
                                <p class="text-lg font-black tracking-normal text-slate-950">{{ $group->prayers_count }}</p>
                                <p class="text-xs font-bold text-rose-900">prayers</p>
                            </div>
                        </div>
                        <a href="{{ route('community-groups.show', $group->slug) }}" class="mt-5 btn-primary w-full bg-indigo-700 hover:bg-indigo-800"><x-ui.icon name="chevron-right" class="h-4 w-4" /> Open group</a>
                    </article>
                @empty
                    <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600 md:col-span-2">Create or join a group to begin.</p>
                @endforelse
            </div>
        </section>
    </section>

    @if ($discoverableGroups->isNotEmpty())
        <section>
            <div class="mb-4">
                <p class="app-eyebrow border-sky-200 bg-sky-50 text-sky-900"><x-ui.icon name="search" class="h-4 w-4" /> Discover</p>
                <h2 class="mt-3 app-section-title">Public ministry groups</h2>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($discoverableGroups as $group)
                    <article class="app-panel border-sky-200">
                        <p class="rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-sky-900">{{ $group->typeLabel() }}</p>
                        <h3 class="mt-3 text-xl font-black tracking-normal text-slate-950">{{ $group->name }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $group->description ?: 'Public MannaRise group.' }}</p>
                        <p class="mt-3 text-sm font-black text-sky-900">{{ $group->memberships_count }} members &middot; {{ $group->reading_challenges_count }} challenges</p>
                        <button type="button" wire:click="joinPublicGroup({{ $group->id }})" class="mt-4 btn-secondary border-sky-200 text-sky-900 hover:bg-sky-50">Join group</button>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
</div>
