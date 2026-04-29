<?php

namespace App\Livewire\Admin;

use App\Models\Devotional;
use App\Models\FeaturedContentSlot;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class FeaturedContent extends Component
{
    public array $slotDevotionals = [];

    public array $slotActive = [];

    public array $slotStartsAt = [];

    public array $slotEndsAt = [];

    public function mount(): void
    {
        FeaturedContentSlot::syncDefaults();
        $this->loadSlots();
    }

    public function saveSlot(string $slotKey): void
    {
        $slot = FeaturedContentSlot::where('slot_key', $slotKey)->firstOrFail();

        $validated = Validator::make([
            'devotional_id' => $this->slotDevotionals[$slotKey] ?? null,
            'is_active' => $this->slotActive[$slotKey] ?? false,
            'starts_at' => $this->slotStartsAt[$slotKey] ?? null,
            'ends_at' => $this->slotEndsAt[$slotKey] ?? null,
        ], [
            'devotional_id' => ['nullable', 'exists:devotionals,id'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ])->validate();

        $slot->update([
            'devotional_id' => blank($validated['devotional_id']) ? null : (int) $validated['devotional_id'],
            'is_active' => (bool) $validated['is_active'],
            'starts_at' => blank($validated['starts_at']) ? null : $validated['starts_at'],
            'ends_at' => blank($validated['ends_at']) ? null : $validated['ends_at'],
        ]);

        $this->loadSlots();
        session()->flash('status', "{$slot->label} updated.");
    }

    public function clearSlot(string $slotKey): void
    {
        $slot = FeaturedContentSlot::where('slot_key', $slotKey)->firstOrFail();

        $slot->update([
            'devotional_id' => null,
            'is_active' => false,
            'starts_at' => null,
            'ends_at' => null,
        ]);

        $this->loadSlots();
        session()->flash('status', "{$slot->label} cleared.");
    }

    public function render()
    {
        FeaturedContentSlot::syncDefaults();

        return view('livewire.admin.featured-content', [
            'slots' => FeaturedContentSlot::with('devotional.category')->orderBy('id')->get(),
            'devotionals' => Devotional::query()
                ->with('category')
                ->where('is_published', true)
                ->orderByDesc('published_at')
                ->orderBy('title')
                ->get(),
        ]);
    }

    private function loadSlots(): void
    {
        FeaturedContentSlot::with('devotional')->get()->each(function (FeaturedContentSlot $slot): void {
            $this->slotDevotionals[$slot->slot_key] = $slot->devotional_id ? (string) $slot->devotional_id : '';
            $this->slotActive[$slot->slot_key] = $slot->is_active;
            $this->slotStartsAt[$slot->slot_key] = $slot->starts_at?->format('Y-m-d\TH:i') ?? '';
            $this->slotEndsAt[$slot->slot_key] = $slot->ends_at?->format('Y-m-d\TH:i') ?? '';
        });
    }
}
