<?php

namespace App\Livewire\Admin;

use App\Models\ResourceCategory;
use App\Models\ResourceItem;
use App\Services\ResourceHub\ResourceHubService;
use App\Support\Toast;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ResourceItems extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $editingId = null;

    public string $resource_category_id = '';

    public string $title = '';

    public string $slug = '';

    public string $excerpt = '';

    public string $description = '';

    public string $content = '';

    public string $type = 'article';

    public string $source_name = 'MannaRise';

    public string $source_url = '';

    public string $external_id = '';

    public string $author = '';

    public string $thumbnail_url = '';

    public string $media_url = '';

    public string $embed_url = '';

    public string $language = 'en';

    public string $license = '';

    public string $tags = '';

    public bool $is_featured = false;

    public bool $is_published = true;

    public string $published_at = '';

    public function mount(): void
    {
        $this->published_at = now()->format('Y-m-d\TH:i');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function save(): void
    {
        $this->slug = $this->slug !== '' ? Str::slug($this->slug) : ResourceItem::uniqueSlug($this->title, $this->editingId);
        $this->embed_url = $this->embed_url ?: (ResourceHubService::embedFromYouTube($this->source_url) ?: '');

        $validated = $this->validate([
            'resource_category_id' => ['nullable', 'exists:resource_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('resource_items', 'slug')->ignore($this->editingId)],
            'excerpt' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:40'],
            'source_name' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:2048'],
            'external_id' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'thumbnail_url' => ['nullable', 'url', 'max:2048'],
            'media_url' => ['nullable', 'url', 'max:2048'],
            'embed_url' => ['nullable', 'url', 'max:2048'],
            'language' => ['required', 'string', 'max:12'],
            'license' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $payload = [
            ...$validated,
            'resource_category_id' => $validated['resource_category_id'] ?: null,
            'external_id' => $validated['external_id'] ?: 'manual-'.Str::slug($validated['title']),
            'source_name' => $validated['source_name'] ?: 'MannaRise',
            'tags' => collect(explode(',', $this->tags))->map(fn ($tag) => trim($tag))->filter()->values()->all(),
            'metadata' => [],
            'published_at' => $this->published_at ?: null,
        ];

        unset($payload['tags']);
        $payload['tags'] = collect(explode(',', $this->tags))->map(fn ($tag) => trim($tag))->filter()->values()->all();

        ResourceItem::updateOrCreate(['id' => $this->editingId], $payload);
        $this->resetForm();
        Toast::status($this, 'Resource item saved.');
    }

    public function edit(int $id): void
    {
        $item = ResourceItem::findOrFail($id);
        $this->editingId = $item->id;
        $this->resource_category_id = (string) $item->resource_category_id;
        $this->title = $item->title;
        $this->slug = $item->slug;
        $this->excerpt = $item->excerpt ?? '';
        $this->description = $item->description ?? '';
        $this->content = $item->content ?? '';
        $this->type = $item->type;
        $this->source_name = $item->source_name ?? 'MannaRise';
        $this->source_url = $item->source_url ?? '';
        $this->external_id = $item->external_id ?? '';
        $this->author = $item->author ?? '';
        $this->thumbnail_url = $item->thumbnail_url ?? '';
        $this->media_url = $item->media_url ?? '';
        $this->embed_url = $item->embed_url ?? '';
        $this->language = $item->language;
        $this->license = $item->license ?? '';
        $this->tags = collect($item->tags ?? [])->join(', ');
        $this->is_featured = $item->is_featured;
        $this->is_published = $item->is_published;
        $this->published_at = $item->published_at?->format('Y-m-d\TH:i') ?? '';
    }

    public function delete(int $id): void
    {
        ResourceItem::findOrFail($id)->delete();
        Toast::status($this, 'Resource item deleted.');
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'resource_category_id', 'title', 'slug', 'excerpt', 'description', 'content', 'source_url', 'external_id', 'author', 'thumbnail_url', 'media_url', 'embed_url', 'license', 'tags']);
        $this->type = 'article';
        $this->source_name = 'MannaRise';
        $this->language = 'en';
        $this->is_featured = false;
        $this->is_published = true;
        $this->published_at = now()->format('Y-m-d\TH:i');
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.resource-items', [
            'categories' => ResourceCategory::active()->orderBy('name')->get(),
            'items' => ResourceItem::with('category')
                ->when($this->search !== '', fn ($query) => $query->where('title', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(10),
        ]);
    }
}
