<?php

namespace App\Livewire\Admin;

use App\Models\Devotional;
use App\Models\DevotionalCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Devotionals extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $editingId = null;

    public string $devotional_category_id = '';

    public string $title = '';

    public string $slug = '';

    public string $bible_reference = '';

    public string $bible_text = '';

    public string $content = '';

    public string $reflection_question = '';

    public string $prayer_point = '';

    public string $declaration = '';

    public string $published_at = '';

    public bool $is_featured = false;

    public bool $is_published = false;

    public int $reading_time = 5;

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
        $this->slug = $this->slug !== '' ? Str::slug($this->slug) : Str::slug($this->title);

        $validated = $this->validate([
            'devotional_category_id' => ['required', 'exists:devotional_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('devotionals', 'slug')->ignore($this->editingId)],
            'bible_reference' => ['nullable', 'string', 'max:255'],
            'bible_text' => ['nullable', 'string'],
            'content' => ['required', 'string', 'min:20'],
            'reflection_question' => ['nullable', 'string'],
            'prayer_point' => ['nullable', 'string'],
            'declaration' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'reading_time' => ['required', 'integer', 'min:1', 'max:120'],
        ]);

        $payload = [
            ...$validated,
            'user_id' => auth()->id(),
            'published_at' => $this->published_at ?: null,
        ];

        if ($this->editingId) {
            Devotional::findOrFail($this->editingId)->update($payload);
        } else {
            Devotional::create($payload);
        }

        $this->resetForm();
        session()->flash('status', 'Devotional saved.');
    }

    public function edit(int $id): void
    {
        $devotional = Devotional::findOrFail($id);

        $this->editingId = $devotional->id;
        $this->devotional_category_id = (string) $devotional->devotional_category_id;
        $this->title = $devotional->title;
        $this->slug = $devotional->slug;
        $this->bible_reference = $devotional->bible_reference ?? '';
        $this->bible_text = $devotional->bible_text ?? '';
        $this->content = $devotional->content;
        $this->reflection_question = $devotional->reflection_question ?? '';
        $this->prayer_point = $devotional->prayer_point ?? '';
        $this->declaration = $devotional->declaration ?? '';
        $this->published_at = $devotional->published_at?->format('Y-m-d\TH:i') ?? '';
        $this->is_featured = $devotional->is_featured;
        $this->is_published = $devotional->is_published;
        $this->reading_time = $devotional->reading_time;
    }

    public function togglePublished(int $id): void
    {
        $devotional = Devotional::findOrFail($id);
        $devotional->update([
            'is_published' => ! $devotional->is_published,
            'published_at' => $devotional->published_at ?? now(),
        ]);
    }

    public function toggleFeatured(int $id): void
    {
        $devotional = Devotional::findOrFail($id);
        $devotional->update(['is_featured' => ! $devotional->is_featured]);
    }

    public function delete(int $id): void
    {
        Devotional::findOrFail($id)->delete();
        session()->flash('status', 'Devotional deleted.');
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->devotional_category_id = '';
        $this->title = '';
        $this->slug = '';
        $this->bible_reference = '';
        $this->bible_text = '';
        $this->content = '';
        $this->reflection_question = '';
        $this->prayer_point = '';
        $this->declaration = '';
        $this->published_at = now()->format('Y-m-d\TH:i');
        $this->is_featured = false;
        $this->is_published = false;
        $this->reading_time = 5;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.devotionals', [
            'categories' => DevotionalCategory::orderBy('name')->get(),
            'devotionals' => Devotional::with('category')
                ->when($this->search !== '', fn ($query) => $query->where('title', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(8),
        ]);
    }
}
