<div class="space-y-6 sm:space-y-8">
    <div class="page-hero border-violet-200">
        <div class="color-strip rounded-none"><span class="bg-violet-500"></span><span class="bg-purple-500"></span><span class="bg-fuchsia-500"></span><span class="bg-emerald-500"></span><span class="bg-amber-400"></span></div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow border-violet-200 bg-violet-50 text-violet-900"><x-ui.icon name="headphones" class="h-4 w-4" /> Admin audio</p>
            <h1 class="mt-3 app-section-title">Manage audio devotionals</h1>
            <p class="mt-2 text-sm text-slate-600">Add audio URLs, link recordings to written devotionals, and publish listening content.</p>
        </div>
    </div>

    <form wire:submit="save" class="app-panel border-violet-200 bg-violet-50">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-black tracking-normal text-slate-950">{{ $editingId ? 'Edit audio devotional' : 'New audio devotional' }}</h2>
            @if ($editingId)<button type="button" wire:click="resetForm" class="btn-secondary border-slate-300 px-3">Cancel edit</button>@endif
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-bold text-slate-700">Linked devotional</label>
                <select wire:model="devotional_id" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                    <option value="">Standalone audio</option>
                    @foreach ($devotionals as $devotional)<option value="{{ $devotional->id }}">{{ $devotional->title }}</option>@endforeach
                </select>
                @error('devotional_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Title</label>
                <input type="text" wire:model="title" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Slug</label>
                <input type="text" wire:model="slug" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                @error('slug') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Speaker</label>
                <input type="text" wire:model="speaker" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                @error('speaker') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Audio URL</label>
                <input type="text" wire:model="audio_url" placeholder="https://... or /storage/audio/file.mp3" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                @error('audio_url') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Duration in seconds</label>
                <input type="number" min="1" wire:model="duration_seconds" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                @error('duration_seconds') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Published at</label>
                <input type="datetime-local" wire:model="published_at" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                @error('published_at') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-bold text-slate-700">Description</label>
            <textarea wire:model="description" rows="4" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100"></textarea>
            @error('description') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="mt-5 flex flex-wrap items-center gap-4">
            <label class="flex items-center gap-2 rounded-xl border border-violet-200 bg-white px-3 py-3 text-sm font-bold text-slate-700"><input type="checkbox" wire:model="is_published" class="rounded border-violet-300 text-violet-700 focus:ring-violet-600"> Published</label>
            <button type="submit" class="btn-primary bg-violet-700 hover:bg-violet-800">Save audio</button>
        </div>
    </form>

    <section>
        <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl font-black tracking-normal text-slate-950">Audio library</h2>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search audio" class="field-input max-w-md border-violet-300 focus:border-violet-600 focus:ring-violet-100">
        </div>

        <div class="table-shell border-violet-200">
            <table class="min-w-full divide-y divide-violet-100 text-sm">
                <thead class="bg-violet-50 text-left text-violet-900"><tr><th class="px-4 py-3 font-black">Title</th><th class="px-4 py-3 font-black">Linked</th><th class="px-4 py-3 font-black">Status</th><th class="px-4 py-3 font-black"></th></tr></thead>
                <tbody class="divide-y divide-violet-100">
                    @forelse ($audioDevotionals as $audio)
                        <tr>
                            <td class="px-4 py-3"><p class="font-black tracking-normal text-slate-950">{{ $audio->title }}</p><p class="text-slate-500">{{ $audio->slug }}</p></td>
                            <td class="px-4 py-3 font-bold text-slate-700">{{ $audio->devotional?->title ?: 'Standalone' }}</td>
                            <td class="px-4 py-3 font-bold text-slate-700">{{ $audio->is_published ? 'Published' : 'Draft' }}</td>
                            <td class="px-4 py-3"><div class="flex flex-wrap justify-end gap-2"><button type="button" wire:click="togglePublished({{ $audio->id }})" class="rounded-full border border-slate-300 px-3 py-1.5 font-bold text-slate-800 hover:bg-slate-50">{{ $audio->is_published ? 'Unpublish' : 'Publish' }}</button><button type="button" wire:click="edit({{ $audio->id }})" class="rounded-full border border-slate-300 px-3 py-1.5 font-bold text-slate-800 hover:bg-slate-50">Edit</button><button type="button" wire:click="delete({{ $audio->id }})" wire:confirm="Delete this audio devotional?" class="rounded-full border border-red-200 px-3 py-1.5 font-bold text-red-700 hover:bg-red-50">Delete</button></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6"><x-ui.empty-state title="No audio devotionals yet" message="Create your first audio devotional using the form above." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">{{ $audioDevotionals->links() }}</div>
    </section>
</div>
