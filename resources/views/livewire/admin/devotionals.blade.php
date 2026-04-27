<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-amber-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-amber-400"></span>
            <span class="bg-yellow-400"></span>
            <span class="bg-lime-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-violet-500"></span>
        </div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow border-amber-200 bg-amber-50 text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Admin devotionals</p>
            <h1 class="mt-3 app-section-title">Manage devotionals</h1>
            <p class="mt-2 text-sm text-slate-600">Create, publish, feature, and edit devotional content.</p>
        </div>
    </div>

    <form wire:submit="save" class="app-panel border-amber-200 bg-amber-50">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-black tracking-normal text-slate-950">{{ $editingId ? 'Edit devotional' : 'New devotional' }}</h2>
            @if ($editingId)
                <button type="button" wire:click="resetForm" class="btn-secondary border-slate-300 px-3">Cancel edit</button>
            @endif
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-bold text-slate-700">Category</label>
                <select wire:model="devotional_category_id" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                    <option value="">Choose category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('devotional_category_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Title</label>
                <input type="text" wire:model="title" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Slug</label>
                <input type="text" wire:model="slug" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                @error('slug') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Bible reference</label>
                <input type="text" wire:model="bible_reference" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                @error('bible_reference') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Published at</label>
                <input type="datetime-local" wire:model="published_at" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                @error('published_at') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Reading time</label>
                <input type="number" min="1" wire:model="reading_time" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                @error('reading_time') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-bold text-slate-700">Bible text</label>
            <textarea wire:model="bible_text" rows="3" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100"></textarea>
            @error('bible_text') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4">
            <label class="block text-sm font-bold text-slate-700">Content</label>
            <textarea wire:model="content" rows="8" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100"></textarea>
            @error('content') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-3">
            <div>
                <label class="block text-sm font-bold text-slate-700">Reflection question</label>
                <textarea wire:model="reflection_question" rows="4" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100"></textarea>
                @error('reflection_question') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Prayer point</label>
                <textarea wire:model="prayer_point" rows="4" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100"></textarea>
                @error('prayer_point') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Declaration</label>
                <textarea wire:model="declaration" rows="4" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100"></textarea>
                @error('declaration') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-5 flex flex-wrap items-center gap-4">
            <label class="flex items-center gap-2 rounded-xl border border-amber-200 bg-white px-3 py-3 text-sm font-bold text-slate-700">
                <input type="checkbox" wire:model="is_published" class="rounded border-amber-300 text-amber-700 focus:ring-amber-600">
                <x-ui.icon name="sparkles" class="h-4 w-4" /> Published
            </label>
            <label class="flex items-center gap-2 rounded-xl border border-amber-200 bg-white px-3 py-3 text-sm font-bold text-slate-700">
                <input type="checkbox" wire:model="is_featured" class="rounded border-amber-300 text-amber-700 focus:ring-amber-600">
                <x-ui.icon name="star" class="h-4 w-4" /> Featured
            </label>
            <button type="submit" class="btn-primary bg-amber-600 text-slate-950 hover:bg-amber-500">Save devotional</button>
        </div>
    </form>

    <section>
        <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl font-black tracking-normal text-slate-950">Devotional library</h2>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search titles" class="field-input max-w-md border-amber-300 focus:border-amber-600 focus:ring-amber-100">
        </div>

        <div class="overflow-x-auto rounded-xl border border-amber-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-amber-100 text-sm">
                <thead class="bg-amber-50 text-left text-amber-900">
                    <tr>
                        <th class="px-4 py-3 font-black">Title</th>
                        <th class="px-4 py-3 font-black">Category</th>
                        <th class="px-4 py-3 font-black">Status</th>
                        <th class="px-4 py-3 font-black"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100">
                    @forelse ($devotionals as $devotional)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-black tracking-normal text-slate-950">{{ $devotional->title }}</p>
                                <p class="text-slate-500">{{ $devotional->slug }}</p>
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-700">{{ $devotional->category?->name }}</td>
                            <td class="px-4 py-3 font-bold text-slate-700">
                                {{ $devotional->is_published ? 'Published' : 'Draft' }}
                                @if ($devotional->is_featured) · Featured @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <button type="button" wire:click="togglePublished({{ $devotional->id }})" class="rounded-full border border-slate-300 px-3 py-1.5 font-bold text-slate-800 hover:bg-slate-50">{{ $devotional->is_published ? 'Unpublish' : 'Publish' }}</button>
                                    <button type="button" wire:click="toggleFeatured({{ $devotional->id }})" class="rounded-full border border-slate-300 px-3 py-1.5 font-bold text-slate-800 hover:bg-slate-50">{{ $devotional->is_featured ? 'Unfeature' : 'Feature' }}</button>
                                    <button type="button" wire:click="edit({{ $devotional->id }})" class="rounded-full border border-slate-300 px-3 py-1.5 font-bold text-slate-800 hover:bg-slate-50">Edit</button>
                                    <button type="button" wire:click="delete({{ $devotional->id }})" wire:confirm="Delete this devotional?" class="rounded-full border border-red-200 px-3 py-1.5 font-bold text-red-700 hover:bg-red-50">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-slate-600">No devotionals yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">{{ $devotionals->links() }}</div>
    </section>
</div>
