<?php

namespace App\Livewire\Admin;

use App\Models\DailyDevotion;
use App\Support\Toast;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class DailyDevotions extends Component
{
    use WithPagination;

    public ?int $editingId = null;

    public string $title = '';

    public string $slug = '';

    public string $bible_reference = '';

    public string $bible_text = '';

    public string $memory_verse = '';

    public string $devotion_text = '';

    public string $prayer = '';

    public string $reflection_questions = '';

    public string $action_point = '';

    public string $devotion_date = '';

    public string $author = 'MannaRise';

    public bool $is_published = true;

    public function mount(): void
    {
        $this->devotion_date = today()->toDateString();
    }

    public function save(): void
    {
        $this->slug = $this->slug !== '' ? Str::slug($this->slug) : DailyDevotion::uniqueSlug($this->title, $this->editingId);

        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('daily_devotions', 'slug')->ignore($this->editingId)],
            'bible_reference' => ['nullable', 'string', 'max:255'],
            'bible_text' => ['nullable', 'string'],
            'memory_verse' => ['nullable', 'string'],
            'devotion_text' => ['required', 'string', 'min:20'],
            'prayer' => ['nullable', 'string'],
            'reflection_questions' => ['nullable', 'string'],
            'action_point' => ['nullable', 'string'],
            'devotion_date' => ['required', 'date', Rule::unique('daily_devotions', 'devotion_date')->ignore($this->editingId)],
            'author' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
        ]);

        $payload = [
            ...$validated,
            'reflection_questions' => collect(preg_split('/\r\n|\r|\n/', $this->reflection_questions))->map(fn ($line) => trim($line))->filter()->values()->all(),
        ];

        DailyDevotion::updateOrCreate(['id' => $this->editingId], $payload);
        $this->resetForm();
        Toast::status($this, 'Daily devotion saved.');
    }

    public function edit(int $id): void
    {
        $devotion = DailyDevotion::findOrFail($id);
        $this->editingId = $devotion->id;
        $this->title = $devotion->title;
        $this->slug = $devotion->slug;
        $this->bible_reference = $devotion->bible_reference ?? '';
        $this->bible_text = $devotion->bible_text ?? '';
        $this->memory_verse = $devotion->memory_verse ?? '';
        $this->devotion_text = $devotion->devotion_text;
        $this->prayer = $devotion->prayer ?? '';
        $this->reflection_questions = collect($devotion->reflection_questions ?? [])->join("\n");
        $this->action_point = $devotion->action_point ?? '';
        $this->devotion_date = $devotion->devotion_date->toDateString();
        $this->author = $devotion->author ?? 'MannaRise';
        $this->is_published = $devotion->is_published;
    }

    public function delete(int $id): void
    {
        DailyDevotion::findOrFail($id)->delete();
        Toast::status($this, 'Daily devotion deleted.');
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'title', 'slug', 'bible_reference', 'bible_text', 'memory_verse', 'devotion_text', 'prayer', 'reflection_questions', 'action_point']);
        $this->devotion_date = today()->toDateString();
        $this->author = 'MannaRise';
        $this->is_published = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.daily-devotions', [
            'devotions' => DailyDevotion::latest('devotion_date')->paginate(10),
        ]);
    }
}
