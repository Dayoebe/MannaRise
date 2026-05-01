<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-sky-200 p-0">
        <div class="color-strip rounded-none"><span class="bg-sky-500"></span><span class="bg-emerald-500"></span><span class="bg-amber-400"></span><span class="bg-violet-500"></span></div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow border-sky-200 bg-sky-50 text-sky-900"><x-ui.icon name="library" class="h-4 w-4" /> Resource Hub admin</p>
            <h1 class="mt-3 app-section-title">Resource items</h1>
            <p class="mt-2 text-sm text-slate-600">Create manual books, videos, audios, sermons, articles, and imported resources.</p>
        </div>
    </section>

    <form wire:submit="save" class="app-panel border-sky-200 bg-sky-50">
        <div class="flex items-center justify-between gap-3"><h2 class="text-xl font-black tracking-normal text-slate-950">{{ $editingId ? 'Edit resource' : 'New resource' }}</h2>@if ($editingId)<button type="button" wire:click="resetForm" class="btn-secondary border-slate-300 px-3">Cancel</button>@endif</div>
        <div class="mt-5 grid gap-4 md:grid-cols-3">
            <label class="block md:col-span-2"><span class="text-sm font-bold text-slate-700">Title</span><input type="text" wire:model="title" class="field-input mt-1 border-sky-300">@error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Type</span><select wire:model="type" class="field-input mt-1 border-sky-300"><option value="article">Article</option><option value="book">Book</option><option value="video">Video</option><option value="audio">Audio</option><option value="sermon">Sermon</option><option value="bible">Bible</option><option value="course">Course</option><option value="education">Education</option></select>@error('type') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Category</span><select wire:model="resource_category_id" class="field-input mt-1 border-sky-300"><option value="">None</option>@foreach ($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select>@error('resource_category_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Slug</span><input type="text" wire:model="slug" class="field-input mt-1 border-sky-300">@error('slug') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Author/Speaker</span><input type="text" wire:model="author" class="field-input mt-1 border-sky-300">@error('author') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
        </div>
        <label class="mt-4 block"><span class="text-sm font-bold text-slate-700">Excerpt</span><textarea wire:model="excerpt" rows="2" class="field-input mt-1 border-sky-300"></textarea>@error('excerpt') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
        <label class="mt-4 block"><span class="text-sm font-bold text-slate-700">Description</span><textarea wire:model="description" rows="4" class="field-input mt-1 border-sky-300"></textarea>@error('description') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
        <label class="mt-4 block"><span class="text-sm font-bold text-slate-700">Readable content</span><textarea wire:model="content" rows="7" class="field-input mt-1 border-sky-300"></textarea>@error('content') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
        <div class="mt-4 grid gap-4 md:grid-cols-3">
            <label class="block"><span class="text-sm font-bold text-slate-700">Source name</span><input type="text" wire:model="source_name" class="field-input mt-1 border-sky-300">@error('source_name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Source URL</span><input type="url" wire:model="source_url" class="field-input mt-1 border-sky-300">@error('source_url') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">External ID</span><input type="text" wire:model="external_id" class="field-input mt-1 border-sky-300">@error('external_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Thumbnail URL</span><input type="url" wire:model="thumbnail_url" class="field-input mt-1 border-sky-300">@error('thumbnail_url') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Media URL</span><input type="url" wire:model="media_url" class="field-input mt-1 border-sky-300">@error('media_url') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Embed URL</span><input type="url" wire:model="embed_url" class="field-input mt-1 border-sky-300">@error('embed_url') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Language</span><input type="text" wire:model="language" class="field-input mt-1 border-sky-300">@error('language') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">License</span><input type="text" wire:model="license" class="field-input mt-1 border-sky-300">@error('license') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
            <label class="block"><span class="text-sm font-bold text-slate-700">Published at</span><input type="datetime-local" wire:model="published_at" class="field-input mt-1 border-sky-300">@error('published_at') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
        </div>
        <label class="mt-4 block"><span class="text-sm font-bold text-slate-700">Tags</span><input type="text" wire:model="tags" placeholder="prayer, bible, learning" class="field-input mt-1 border-sky-300">@error('tags') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror</label>
        <div class="mt-5 flex flex-wrap items-center gap-4"><label class="flex items-center gap-2 rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm font-bold"><input type="checkbox" wire:model="is_published"> Published</label><label class="flex items-center gap-2 rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm font-bold"><input type="checkbox" wire:model="is_featured"> Featured</label><button type="submit" class="btn-primary bg-sky-700 hover:bg-sky-800">Save resource</button></div>
    </form>

    <section>
        <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between"><h2 class="text-2xl font-black tracking-normal text-slate-950">Resources</h2><input type="search" wire:model.live.debounce.300ms="search" placeholder="Search resources" class="field-input max-w-md border-sky-300"></div>
        <div class="overflow-x-auto rounded-xl border border-sky-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-sky-100 text-sm"><thead class="bg-sky-50 text-left text-sky-900"><tr><th class="px-4 py-3 font-black">Title</th><th class="px-4 py-3 font-black">Type</th><th class="px-4 py-3 font-black">Source</th><th class="px-4 py-3"></th></tr></thead><tbody class="divide-y divide-sky-100">
                @forelse ($items as $item)
                    <tr><td class="px-4 py-3"><p class="font-black text-slate-950">{{ $item->title }}</p><p class="text-slate-500">{{ $item->slug }}</p></td><td class="px-4 py-3 font-bold">{{ $item->type }}</td><td class="px-4 py-3 font-bold">{{ $item->source_name }}</td><td class="px-4 py-3"><div class="flex justify-end gap-2"><button type="button" wire:click="edit({{ $item->id }})" class="rounded-full border border-slate-300 px-3 py-1.5 font-bold">Edit</button><button type="button" wire:click="delete({{ $item->id }})" wire:confirm="Delete this resource?" class="rounded-full border border-red-200 px-3 py-1.5 font-bold text-red-700">Delete</button></div></td></tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-slate-600">No resources yet.</td></tr>
                @endforelse
            </tbody></table>
        </div>
        <div class="mt-5">{{ $items->links() }}</div>
    </section>
</div>
