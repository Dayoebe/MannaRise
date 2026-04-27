<div class="grid gap-6 lg:grid-cols-[24rem_minmax(0,1fr)]">
    <form wire:submit="save" class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
        <h1 class="text-2xl font-semibold text-stone-950">{{ $editingId ? 'Edit category' : 'New category' }}</h1>

        @error('category') <p class="mt-4 rounded-md bg-red-50 px-3 py-2 text-sm text-red-700">{{ $message }}</p> @enderror

        <div class="mt-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-stone-700">Name</label>
                <input type="text" wire:model="name" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700">Slug</label>
                <input type="text" wire:model="slug" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('slug') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700">Description</label>
                <textarea wire:model="description" rows="5" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"></textarea>
                @error('description') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-2 text-sm text-stone-700">
                <input type="checkbox" wire:model="is_active" class="rounded border-stone-300 text-emerald-700 focus:ring-emerald-600">
                Active
            </label>
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
            <h2 class="text-3xl font-semibold text-stone-950">Categories</h2>
            <p class="mt-2 text-sm text-stone-600">Organize devotionals by topic.</p>
        </div>

        <div class="overflow-hidden rounded-lg border border-stone-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-50 text-left text-stone-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Name</th>
                        <th class="px-4 py-3 font-semibold">Readings</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-stone-950">{{ $category->name }}</p>
                                <p class="text-stone-500">{{ $category->slug }}</p>
                            </td>
                            <td class="px-4 py-3 text-stone-700">{{ $category->devotionals_count }}</td>
                            <td class="px-4 py-3 text-stone-700">{{ $category->is_active ? 'Active' : 'Hidden' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="edit({{ $category->id }})" class="rounded-md border border-stone-300 px-3 py-1.5 font-semibold text-stone-800 hover:bg-stone-100">Edit</button>
                                    <button type="button" wire:click="delete({{ $category->id }})" wire:confirm="Delete this category?" class="rounded-md border border-red-200 px-3 py-1.5 font-semibold text-red-700 hover:bg-red-50">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-stone-600">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
