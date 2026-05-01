<div class="grid gap-5 lg:grid-cols-[minmax(0,24rem)_minmax(0,1fr)] lg:items-start">
    <form wire:submit="save" class="app-panel border-mauve-200 bg-mauve-50 lg:sticky lg:top-36">
        <p class="app-eyebrow border-mauve-200 bg-white text-mauve-900"><x-ui.icon name="journal" class="h-4 w-4" /> Journal</p>
        <h1 class="mt-3 text-2xl font-black tracking-normal text-slate-950">{{ $editingId ? 'Edit journal entry' : 'New journal entry' }}</h1>

        <div class="mt-5 space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700">Devotional</label>
                <select wire:model="devotional_id" class="field-input mt-1 border-mauve-300 focus:border-mauve-600 focus:ring-mauve-100">
                    <option value="">No linked devotional</option>
                    @foreach ($devotionals as $devotional)
                        <option value="{{ $devotional->id }}">{{ $devotional->title }}</option>
                    @endforeach
                </select>
                @error('devotional_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700">Title</label>
                <input type="text" wire:model="title" class="field-input mt-1 border-mauve-300 focus:border-mauve-600 focus:ring-mauve-100">
                @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700">Date</label>
                <input type="date" wire:model="entry_date" class="field-input mt-1 border-mauve-300 focus:border-mauve-600 focus:ring-mauve-100">
                @error('entry_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                <div>
                    <label class="block text-sm font-bold text-slate-700">Mood</label>
                    <input type="text" wire:model="mood" placeholder="Peaceful, anxious, hopeful" class="field-input mt-1 border-mauve-300 focus:border-mauve-600 focus:ring-mauve-100">
                    @error('mood') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700">Topics</label>
                    <input type="text" wire:model="topicsInput" placeholder="faith, family, purpose" class="field-input mt-1 border-mauve-300 focus:border-mauve-600 focus:ring-mauve-100">
                    @error('topicsInput') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700">Reflection</label>
                <textarea wire:model="content" rows="8" class="field-input mt-1 border-mauve-300 focus:border-mauve-600 focus:ring-mauve-100"></textarea>
                @error('content') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-5 flex flex-wrap gap-2">
            <button type="submit" class="btn-primary bg-mauve-700 hover:bg-mauve-800"><x-ui.icon name="send" class="h-4 w-4" /> Save</button>
            @if ($editingId)
                <button type="button" wire:click="resetForm" class="btn-secondary border-slate-300">Cancel</button>
            @endif
        </div>
    </form>

    <section>
        <div class="mb-4 app-panel overflow-hidden border-sky-200 p-0 sm:p-0">
            <div class="color-strip rounded-none">
                <span class="bg-mauve-500"></span>
                <span class="bg-violet-500"></span>
                <span class="bg-sky-500"></span>
                <span class="bg-cyan-500"></span>
                <span class="bg-emerald-500"></span>
            </div>
            <div class="p-5 sm:p-6">
                <p class="app-eyebrow border-sky-200 bg-sky-50 text-sky-900"><x-ui.icon name="journal" class="h-4 w-4" /> Reflections</p>
                <h2 class="mt-3 app-section-title">Reflection journal</h2>
                <p class="mt-2 text-sm text-slate-600">Private entries tied to your devotional growth.</p>
            </div>
        </div>

        <section class="mb-4 app-panel border-indigo-200 bg-indigo-50">
            <p class="app-eyebrow border-indigo-200 bg-white text-indigo-900"><x-ui.icon name="bar-chart" class="h-4 w-4" /> Journal insights</p>
            <h2 class="mt-3 text-xl font-black tracking-normal text-slate-950">This month&apos;s patterns</h2>
            <div class="mt-4 grid gap-3 md:grid-cols-3">
                <div class="rounded-xl border border-white bg-white p-4">
                    <p class="text-2xl font-black text-slate-950">{{ $insights['entry_count'] }}</p>
                    <p class="mt-1 text-sm font-bold text-indigo-900">Journal entries</p>
                </div>
                <div class="rounded-xl border border-white bg-white p-4">
                    <p class="text-sm font-semibold leading-6 text-slate-700">{{ $insights['topic_summary'] }}</p>
                </div>
                <div class="rounded-xl border border-white bg-white p-4">
                    <p class="text-sm font-semibold leading-6 text-slate-700">{{ $insights['prayer_summary'] }}</p>
                </div>
            </div>
            <p class="mt-3 rounded-xl border border-white bg-white p-4 text-sm font-semibold leading-6 text-slate-700">{{ $insights['mood_summary'] }}</p>
        </section>

        <div class="space-y-3">
            @forelse ($entries as $entry)
                <article class="app-panel border-sky-200">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h3 class="text-lg font-black tracking-normal text-slate-950">{{ $entry->title }}</h3>
                            <p class="mt-1 text-sm font-bold text-slate-500">{{ $entry->entry_date->format('M j, Y') }} @if ($entry->devotional) · {{ $entry->devotional->title }} @endif</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @if ($entry->mood)
                                    <span class="rounded-full bg-mauve-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-mauve-900">{{ $entry->mood }}</span>
                                @endif
                                @foreach ($entry->topics ?? [] as $topic)
                                    <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-sky-900">{{ $topic }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" wire:click="edit({{ $entry->id }})" class="btn-secondary min-h-10 border-sky-200 px-3 py-1.5">Edit</button>
                            <button type="button" wire:click="delete({{ $entry->id }})" wire:confirm="Delete this journal entry?" class="inline-flex min-h-10 items-center justify-center rounded-full border border-red-200 bg-white px-3 py-1.5 text-sm font-bold text-red-700 hover:bg-red-50">Delete</button>
                        </div>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $entry->content }}</p>
                </article>
            @empty
                <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600">No journal entries yet.</p>
            @endforelse
        </div>

        <div class="mt-5">{{ $entries->links() }}</div>
    </section>
</div>
