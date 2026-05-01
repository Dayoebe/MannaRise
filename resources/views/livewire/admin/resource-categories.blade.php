<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-emerald-200 p-0">
        <div class="color-strip rounded-none"><span class="bg-emerald-500"></span><span class="bg-sky-500"></span><span class="bg-amber-400"></span><span class="bg-fuchsia-500"></span></div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow border-emerald-200 bg-emerald-50 text-emerald-900"><x-ui.icon name="library" class="h-4 w-4" /> Resource Hub admin</p>
            <h1 class="mt-3 app-section-title">Resource categories</h1>
            <p class="mt-2 text-sm text-slate-600">Organize resources by type, topic, and display icon.</p>
        </div>
    </section>

    <form wire:submit="save" class="app-panel border-emerald-200 bg-emerald-50">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-black tracking-normal text-slate-950">{{ $editingId ? 'Edit category' : 'New category' }}</h2>
            @if ($editingId)<button type="button" wire:click="resetForm" class="btn-secondary border-slate-300 px-3">Cancel</button>@endif
        </div>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <label class="block"><span class="text-sm font-bold text-slate-700">Name</span><input type="text" wire:model="name" class="field-input mt-1 border-emerald-300">@error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Slug</span><input type="text" wire:model="slug" class="field-input mt-1 border-emerald-300">@error('slug') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Type</span><input type="text" wire:model="type" placeholder="book, video, audio..." class="field-input mt-1 border-emerald-300">@error('type') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Icon</span><input type="text" wire:model="icon" placeholder="library" class="field-input mt-1 border-emerald-300">@error('icon') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
        </div>
        <label class="mt-4 block"><span class="text-sm font-bold text-slate-700">Description</span><textarea wire:model="description" rows="3" class="field-input mt-1 border-emerald-300"></textarea>@error('description') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
        <div class="mt-5 flex flex-wrap items-center gap-4">
            <label class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-white px-3 py-3 text-sm font-bold"><input type="checkbox" wire:model="is_active"> Active</label>
            <button type="submit" class="btn-primary bg-emerald-700 hover:bg-emerald-800">Save category</button>
        </div>
    </form>

    <section class="overflow-x-auto rounded-xl border border-emerald-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-emerald-100 text-sm">
            <thead class="bg-emerald-50 text-left text-emerald-900"><tr><th class="px-4 py-3 font-black">Name</th><th class="px-4 py-3 font-black">Type</th><th class="px-4 py-3 font-black">Items</th><th class="px-4 py-3"></th></tr></thead>
            <tbody class="divide-y divide-emerald-100">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3"><p class="font-black text-slate-950">{{ $category->name }}</p><p class="text-slate-500">{{ $category->slug }}</p></td>
                        <td class="px-4 py-3 font-bold text-slate-700">{{ $category->type ?: 'General' }}</td>
                        <td class="px-4 py-3 font-bold text-slate-700">{{ $category->items_count }}</td>
                        <td class="px-4 py-3"><div class="flex justify-end gap-2"><button type="button" wire:click="edit({{ $category->id }})" class="rounded-full border border-slate-300 px-3 py-1.5 font-bold">Edit</button><button type="button" wire:click="delete({{ $category->id }})" wire:confirm="Delete this category?" class="rounded-full border border-red-200 px-3 py-1.5 font-bold text-red-700">Delete</button></div></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-slate-600">No resource categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
    {{ $categories->links() }}
</div>
