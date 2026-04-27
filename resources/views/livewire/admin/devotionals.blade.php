<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-semibold text-stone-950">Manage devotionals</h1>
        <p class="mt-2 text-sm text-stone-600">Create, publish, feature, and edit devotional content.</p>
    </div>

    <form wire:submit="save" class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-stone-950">{{ $editingId ? 'Edit devotional' : 'New devotional' }}</h2>
            @if ($editingId)
                <button type="button" wire:click="resetForm" class="rounded-md border border-stone-300 px-3 py-2 text-sm font-semibold text-stone-800 hover:bg-stone-100">Cancel edit</button>
            @endif
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-stone-700">Category</label>
                <select wire:model="devotional_category_id" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    <option value="">Choose category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('devotional_category_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700">Title</label>
                <input type="text" wire:model="title" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700">Slug</label>
                <input type="text" wire:model="slug" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('slug') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700">Bible reference</label>
                <input type="text" wire:model="bible_reference" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('bible_reference') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700">Published at</label>
                <input type="datetime-local" wire:model="published_at" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('published_at') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700">Reading time</label>
                <input type="number" min="1" wire:model="reading_time" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('reading_time') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-stone-700">Bible text</label>
            <textarea wire:model="bible_text" rows="3" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"></textarea>
            @error('bible_text') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-stone-700">Content</label>
            <textarea wire:model="content" rows="8" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"></textarea>
            @error('content') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-stone-700">Reflection question</label>
                <textarea wire:model="reflection_question" rows="4" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"></textarea>
                @error('reflection_question') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700">Prayer point</label>
                <textarea wire:model="prayer_point" rows="4" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"></textarea>
                @error('prayer_point') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700">Declaration</label>
                <textarea wire:model="declaration" rows="4" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"></textarea>
                @error('declaration') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-5 flex flex-wrap items-center gap-4">
            <label class="flex items-center gap-2 text-sm text-stone-700">
                <input type="checkbox" wire:model="is_published" class="rounded border-stone-300 text-emerald-700 focus:ring-emerald-600">
                Published
            </label>
            <label class="flex items-center gap-2 text-sm text-stone-700">
                <input type="checkbox" wire:model="is_featured" class="rounded border-stone-300 text-emerald-700 focus:ring-emerald-600">
                Featured
            </label>
            <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Save devotional</button>
        </div>
    </form>

    <section>
        <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl font-semibold text-stone-950">Devotional library</h2>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search titles" class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
        </div>

        <div class="overflow-hidden rounded-lg border border-stone-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-50 text-left text-stone-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Title</th>
                        <th class="px-4 py-3 font-semibold">Category</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($devotionals as $devotional)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-stone-950">{{ $devotional->title }}</p>
                                <p class="text-stone-500">{{ $devotional->slug }}</p>
                            </td>
                            <td class="px-4 py-3 text-stone-700">{{ $devotional->category?->name }}</td>
                            <td class="px-4 py-3 text-stone-700">
                                {{ $devotional->is_published ? 'Published' : 'Draft' }}
                                @if ($devotional->is_featured) · Featured @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <button type="button" wire:click="togglePublished({{ $devotional->id }})" class="rounded-md border border-stone-300 px-3 py-1.5 font-semibold text-stone-800 hover:bg-stone-100">{{ $devotional->is_published ? 'Unpublish' : 'Publish' }}</button>
                                    <button type="button" wire:click="toggleFeatured({{ $devotional->id }})" class="rounded-md border border-stone-300 px-3 py-1.5 font-semibold text-stone-800 hover:bg-stone-100">{{ $devotional->is_featured ? 'Unfeature' : 'Feature' }}</button>
                                    <button type="button" wire:click="edit({{ $devotional->id }})" class="rounded-md border border-stone-300 px-3 py-1.5 font-semibold text-stone-800 hover:bg-stone-100">Edit</button>
                                    <button type="button" wire:click="delete({{ $devotional->id }})" wire:confirm="Delete this devotional?" class="rounded-md border border-red-200 px-3 py-1.5 font-semibold text-red-700 hover:bg-red-50">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-stone-600">No devotionals yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">{{ $devotionals->links() }}</div>
    </section>
</div>
