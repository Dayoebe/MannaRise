<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-amber-200 p-0">
        <div class="color-strip rounded-none"><span class="bg-amber-400"></span><span class="bg-emerald-500"></span><span class="bg-sky-500"></span><span class="bg-violet-500"></span></div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow border-amber-200 bg-amber-50 text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Resource Hub admin</p>
            <h1 class="mt-3 app-section-title">Daily devotions</h1>
            <p class="mt-2 text-sm text-slate-600">Prepare devotion guides with scripture, prayer, reflection, and action points.</p>
        </div>
    </section>

    <form wire:submit="save" class="app-panel border-amber-200 bg-amber-50">
        <div class="flex items-center justify-between gap-3"><h2 class="text-xl font-black tracking-normal text-slate-950">{{ $editingId ? 'Edit devotion' : 'New devotion' }}</h2>@if ($editingId)<button type="button" wire:click="resetForm" class="btn-secondary border-slate-300 px-3">Cancel</button>@endif</div>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <label class="block"><span class="text-sm font-bold text-slate-700">Title</span><input type="text" wire:model="title" class="field-input mt-1 border-amber-300">@error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Date</span><input type="date" wire:model="devotion_date" class="field-input mt-1 border-amber-300">@error('devotion_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Slug</span><input type="text" wire:model="slug" class="field-input mt-1 border-amber-300">@error('slug') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Author</span><input type="text" wire:model="author" class="field-input mt-1 border-amber-300">@error('author') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Bible reference</span><input type="text" wire:model="bible_reference" class="field-input mt-1 border-amber-300">@error('bible_reference') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Memory verse</span><input type="text" wire:model="memory_verse" class="field-input mt-1 border-amber-300">@error('memory_verse') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
        </div>
        <label class="mt-4 block"><span class="text-sm font-bold text-slate-700">Bible text</span><textarea wire:model="bible_text" rows="3" class="field-input mt-1 border-amber-300"></textarea>@error('bible_text') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
        <label class="mt-4 block"><span class="text-sm font-bold text-slate-700">Devotion text</span><textarea wire:model="devotion_text" rows="7" class="field-input mt-1 border-amber-300"></textarea>@error('devotion_text') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
        <div class="mt-4 grid gap-4 md:grid-cols-3">
            <label class="block"><span class="text-sm font-bold text-slate-700">Prayer</span><textarea wire:model="prayer" rows="5" class="field-input mt-1 border-amber-300"></textarea>@error('prayer') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Reflection questions</span><textarea wire:model="reflection_questions" rows="5" class="field-input mt-1 border-amber-300" placeholder="One question per line"></textarea>@error('reflection_questions') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Action point</span><textarea wire:model="action_point" rows="5" class="field-input mt-1 border-amber-300"></textarea>@error('action_point') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
        </div>
        <div class="mt-5 flex flex-wrap items-center gap-4"><label class="flex items-center gap-2 rounded-xl border border-amber-200 bg-white px-3 py-3 text-sm font-bold"><input type="checkbox" wire:model="is_published"> Published</label><button type="submit" class="btn-primary bg-amber-600 text-slate-950 hover:bg-amber-500">Save devotion</button></div>
    </form>

    <section class="overflow-x-auto rounded-xl border border-amber-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-amber-100 text-sm"><thead class="bg-amber-50 text-left text-amber-900"><tr><th class="px-4 py-3 font-black">Title</th><th class="px-4 py-3 font-black">Date</th><th class="px-4 py-3 font-black">Status</th><th class="px-4 py-3"></th></tr></thead><tbody class="divide-y divide-amber-100">
            @forelse ($devotions as $devotion)
                <tr><td class="px-4 py-3"><p class="font-black text-slate-950">{{ $devotion->title }}</p><p class="text-slate-500">{{ $devotion->slug }}</p></td><td class="px-4 py-3 font-bold">{{ $devotion->devotion_date->format('M j, Y') }}</td><td class="px-4 py-3 font-bold">{{ $devotion->is_published ? 'Published' : 'Draft' }}</td><td class="px-4 py-3"><div class="flex justify-end gap-2"><button type="button" wire:click="edit({{ $devotion->id }})" class="rounded-full border border-slate-300 px-3 py-1.5 font-bold">Edit</button><button type="button" wire:click="delete({{ $devotion->id }})" wire:confirm="Delete this devotion?" class="rounded-full border border-red-200 px-3 py-1.5 font-bold text-red-700">Delete</button></div></td></tr>
            @empty
                <tr><td colspan="4" class="px-4 py-6 text-slate-600">No daily devotions yet.</td></tr>
            @endforelse
        </tbody></table>
    </section>
    {{ $devotions->links() }}
</div>
