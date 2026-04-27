<div class="grid gap-5 lg:grid-cols-[minmax(0,24rem)_minmax(0,1fr)] lg:items-start">
    <form wire:submit="save" class="app-panel border-olive-200 bg-olive-50 lg:sticky lg:top-36">
        <p class="app-eyebrow border-olive-200 bg-white text-olive-900"><x-ui.icon name="bookmark" class="h-4 w-4" /> Categories</p>
        <h1 class="mt-3 text-2xl font-black tracking-normal text-slate-950">{{ $editingId ? 'Edit category' : 'New category' }}</h1>

        @error('category') <p class="mt-4 rounded-md bg-red-50 px-3 py-2 text-sm text-red-700">{{ $message }}</p> @enderror

        <div class="mt-5 space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700">Name</label>
                <input type="text" wire:model="name" class="field-input mt-1 border-olive-300 focus:border-olive-600 focus:ring-olive-100">
                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Slug</label>
                <input type="text" wire:model="slug" class="field-input mt-1 border-olive-300 focus:border-olive-600 focus:ring-olive-100">
                @error('slug') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Description</label>
                <textarea wire:model="description" rows="5" class="field-input mt-1 border-olive-300 focus:border-olive-600 focus:ring-olive-100"></textarea>
                @error('description') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-2 rounded-xl border border-olive-200 bg-white px-3 py-3 text-sm font-bold text-slate-700">
                <input type="checkbox" wire:model="is_active" class="rounded border-olive-300 text-olive-700 focus:ring-olive-600">
                <x-ui.icon name="sparkles" class="h-4 w-4" /> Active
            </label>
        </div>

        <div class="mt-5 flex flex-wrap gap-2">
            <button type="submit" class="btn-primary bg-olive-700 hover:bg-olive-800">Save</button>
            @if ($editingId)
                <button type="button" wire:click="resetForm" class="btn-secondary border-slate-300">Cancel</button>
            @endif
        </div>
    </form>

    <section>
        <div class="mb-4 app-panel overflow-hidden border-lime-200 p-0 sm:p-0">
            <div class="color-strip rounded-none">
                <span class="bg-lime-500"></span>
                <span class="bg-green-500"></span>
                <span class="bg-emerald-500"></span>
                <span class="bg-teal-500"></span>
                <span class="bg-yellow-400"></span>
            </div>
            <div class="p-5 sm:p-6">
                <p class="app-eyebrow border-lime-200 bg-lime-50 text-lime-900"><x-ui.icon name="bookmark" class="h-4 w-4" /> Topics</p>
                <h2 class="mt-3 app-section-title">Categories</h2>
                <p class="mt-2 text-sm text-slate-600">Organize devotionals by topic.</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-lime-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-lime-100 text-sm">
                <thead class="bg-lime-50 text-left text-lime-900">
                    <tr>
                        <th class="px-4 py-3 font-black">Name</th>
                        <th class="px-4 py-3 font-black">Readings</th>
                        <th class="px-4 py-3 font-black">Status</th>
                        <th class="px-4 py-3 font-black"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-lime-100">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-black tracking-normal text-slate-950">{{ $category->name }}</p>
                                <p class="text-slate-500">{{ $category->slug }}</p>
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-700">{{ $category->devotionals_count }}</td>
                            <td class="px-4 py-3 font-bold text-slate-700">{{ $category->is_active ? 'Active' : 'Hidden' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="edit({{ $category->id }})" class="rounded-full border border-slate-300 px-3 py-1.5 font-bold text-slate-800 hover:bg-slate-50">Edit</button>
                                    <button type="button" wire:click="delete({{ $category->id }})" wire:confirm="Delete this category?" class="rounded-full border border-red-200 px-3 py-1.5 font-bold text-red-700 hover:bg-red-50">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-slate-600">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
