<?php

namespace App\Livewire\Admin;

use App\Models\AudioDevotional;
use App\Models\Devotional;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class AudioDevotionals extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;
    public string $devotional_id = '';
    public string $title = '';
    public string $slug = '';
    public string $description = '';
    public string $audio_url = '';
    public string $speaker = '';
    public ?int $duration_seconds = null;
    public bool $is_published = false;
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
        abort_unless(auth()->user()?->canDo('manage-audio-devotionals'), 403);

        $this->slug = $this->slug !== '' ? Str::slug($this->slug) : Str::slug($this->title);

        $validated = $this->validate([
            'devotional_id' => ['nullable', 'exists:devotionals,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('audio_devotionals', 'slug')->ignore($this->editingId)],
            'description' => ['nullable', 'string'],
            'audio_url' => ['required', 'string', 'max:2048'],
            'speaker' => ['nullable', 'string', 'max:255'],
            'duration_seconds' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $payload = [
            ...$validated,
            'devotional_id' => $validated['devotional_id'] ?: null,
            'user_id' => auth()->id(),
            'published_at' => $this->published_at ?: null,
        ];

        if ($this->editingId) {
            AudioDevotional::findOrFail($this->editingId)->update($payload);
        } else {
            AudioDevotional::create($payload);
        }

        $this->resetForm();
        session()->flash('status', 'Audio devotional saved.');
    }

    public function edit(int $id): void
    {
        abort_unless(auth()->user()?->canDo('manage-audio-devotionals'), 403);

        $audio = AudioDevotional::findOrFail($id);
        $this->editingId = $audio->id;
        $this->devotional_id = (string) ($audio->devotional_id ?? '');
        $this->title = $audio->title;
        $this->slug = $audio->slug;
        $this->description = $audio->description ?? '';
        $this->audio_url = $audio->audio_url;
        $this->speaker = $audio->speaker ?? '';
        $this->duration_seconds = $audio->duration_seconds;
        $this->is_published = $audio->is_published;
        $this->published_at = $audio->published_at?->format('Y-m-d\TH:i') ?? '';
    }

    public function togglePublished(int $id): void
    {
        abort_unless(auth()->user()?->canDo('manage-audio-devotionals'), 403);

        $audio = AudioDevotional::findOrFail($id);
        $audio->update([
            'is_published' => ! $audio->is_published,
            'published_at' => $audio->published_at ?? now(),
        ]);
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()?->canDo('manage-audio-devotionals'), 403);

        AudioDevotional::findOrFail($id)->delete();
        session()->flash('status', 'Audio devotional deleted.');
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->devotional_id = '';
        $this->title = '';
        $this->slug = '';
        $this->description = '';
        $this->audio_url = '';
        $this->speaker = '';
        $this->duration_seconds = null;
        $this->is_published = false;
        $this->published_at = now()->format('Y-m-d\TH:i');
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.audio-devotionals', [
            'devotionals' => Devotional::orderBy('title')->get(),
            'audioDevotionals' => AudioDevotional::with('devotional')
                ->when($this->search !== '', fn ($query) => $query->where('title', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(8),
        ]);
    }
}
