<?php

namespace App\Livewire\PrayerRequests;

use App\Models\PrayerRequest;
use Livewire\Component;
use Livewire\WithPagination;

class Wall extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = 'open';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function pray(int $id): void
    {
        $request = PrayerRequest::where('is_public', true)->findOrFail($id);
        $request->increment('prayed_count');

        session()->flash('status', 'Prayer count updated.');
    }

    public function render()
    {
        return view('livewire.prayer-requests.wall', [
            'requests' => PrayerRequest::query()
                ->where('is_public', true)
                ->when($this->status === 'open', fn ($query) => $query->where('is_answered', false))
                ->when($this->status === 'answered', fn ($query) => $query->where('is_answered', true))
                ->when($this->search !== '', fn ($query) => $query->where(function ($query) {
                    $query
                        ->where('title', 'like', "%{$this->search}%")
                        ->orWhere('body', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%");
                }))
                ->latest()
                ->paginate(9),
            'openCount' => PrayerRequest::where('is_public', true)->where('is_answered', false)->count(),
            'answeredCount' => PrayerRequest::where('is_public', true)->where('is_answered', true)->count(),
        ]);
    }
}
