<div class="grid gap-6 lg:grid-cols-[24rem_minmax(0,1fr)]">
    <form wire:submit="save" class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
        <h1 class="text-2xl font-semibold text-stone-950">{{ $editingId ? 'Edit journal entry' : 'New journal entry' }}</h1>

        <div class="mt-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-stone-700">Devotional</label>
                <select wire:model="devotional_id" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    <option value="">No linked devotional</option>
                    @foreach ($devotionals as $devotional)
                        <option value="{{ $devotional->id }}">{{ $devotional->title }}</option>
                    @endforeach
                </select>
                @error('devotional_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700">Title</label>
                <input type="text" wire:model="title" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700">Date</label>
                <input type="date" wire:model="entry_date" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('entry_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700">Reflection</label>
                <textarea wire:model="content" rows="8" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"></textarea>
                @error('content') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-5 flex flex-wrap gap-2">
            <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Save</button>
            @if ($editingId)
                <button type="button" wire:click="resetForm" class="rounded-md border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-800 hover:bg-stone-100">Cancel</button>
            @endif
        </div>
    </form>

    <section>
        <div class="mb-4">
            <h2 class="text-3xl font-semibold text-stone-950">Reflection journal</h2>
            <p class="mt-2 text-sm text-stone-600">Private entries tied to your devotional growth.</p>
        </div>

        <div class="space-y-3">
            @forelse ($entries as $entry)
                <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-stone-950">{{ $entry->title }}</h3>
                            <p class="mt-1 text-sm text-stone-500">{{ $entry->entry_date->format('M j, Y') }} @if ($entry->devotional) · {{ $entry->devotional->title }} @endif</p>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" wire:click="edit({{ $entry->id }})" class="rounded-md border border-stone-300 px-3 py-1.5 text-sm font-semibold text-stone-800 hover:bg-stone-100">Edit</button>
                            <button type="button" wire:click="delete({{ $entry->id }})" wire:confirm="Delete this journal entry?" class="rounded-md border border-red-200 px-3 py-1.5 text-sm font-semibold text-red-700 hover:bg-red-50">Delete</button>
                        </div>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-stone-600">{{ $entry->content }}</p>
                </article>
            @empty
                <p class="rounded-lg border border-dashed border-stone-300 bg-white p-5 text-sm text-stone-600">No journal entries yet.</p>
            @endforelse
        </div>

        <div class="mt-5">{{ $entries->links() }}</div>
    </section>
</div>
