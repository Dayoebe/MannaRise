<?php

namespace App\Livewire\Admin;

use App\Models\PrayerRequest;
use App\Models\Testimony;
use App\Support\Toast;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ModerationQueue extends Component
{
    public string $status = 'pending';

    public string $search = '';

    public function updatedStatus(): void
    {
        $this->search = trim($this->search);
    }

    public function approvePrayer(int $id): void
    {
        PrayerRequest::findOrFail($id)->update([
            'is_public' => true,
            'moderation_status' => 'approved',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        Toast::status($this, 'Prayer request approved.');
    }

    public function rejectPrayer(int $id): void
    {
        PrayerRequest::findOrFail($id)->update([
            'is_public' => false,
            'moderation_status' => 'rejected',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        Toast::status($this, 'Prayer request rejected.');
    }

    public function queuePrayer(int $id): void
    {
        PrayerRequest::findOrFail($id)->update([
            'is_public' => false,
            'moderation_status' => 'pending',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        Toast::status($this, 'Prayer request returned to review.');
    }

    public function approveTestimony(int $id): void
    {
        Testimony::findOrFail($id)->update([
            'is_approved' => true,
            'moderation_status' => 'approved',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        Toast::status($this, 'Testimony approved.');
    }

    public function rejectTestimony(int $id): void
    {
        Testimony::findOrFail($id)->update([
            'is_approved' => false,
            'moderation_status' => 'rejected',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        Toast::status($this, 'Testimony rejected.');
    }

    public function queueTestimony(int $id): void
    {
        Testimony::findOrFail($id)->update([
            'is_approved' => false,
            'moderation_status' => 'pending',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        Toast::status($this, 'Testimony returned to review.');
    }

    public function render()
    {
        return view('livewire.admin.moderation-queue', [
            'prayers' => $this->prayerQuery()->latest()->take(10)->get(),
            'testimonies' => $this->testimonyQuery()->latest()->take(10)->get(),
            'counts' => [
                'pending' => PrayerRequest::where('moderation_status', 'pending')->count() + Testimony::where('moderation_status', 'pending')->count(),
                'approved' => PrayerRequest::where('moderation_status', 'approved')->count() + Testimony::where('moderation_status', 'approved')->count(),
                'rejected' => PrayerRequest::where('moderation_status', 'rejected')->count() + Testimony::where('moderation_status', 'rejected')->count(),
            ],
        ]);
    }

    private function prayerQuery(): Builder
    {
        return PrayerRequest::query()
            ->with('room')
            ->when($this->status !== 'all', fn (Builder $query) => $query->where('moderation_status', $this->status))
            ->when($this->search !== '', fn (Builder $query) => $query->where(function (Builder $query): void {
                $query
                    ->where('title', 'like', "%{$this->search}%")
                    ->orWhere('body', 'like', "%{$this->search}%")
                    ->orWhere('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            }));
    }

    private function testimonyQuery(): Builder
    {
        return Testimony::query()
            ->when($this->status !== 'all', fn (Builder $query) => $query->where('moderation_status', $this->status))
            ->when($this->search !== '', fn (Builder $query) => $query->where(function (Builder $query): void {
                $query
                    ->where('title', 'like', "%{$this->search}%")
                    ->orWhere('body', 'like', "%{$this->search}%")
                    ->orWhere('before_body', 'like', "%{$this->search}%")
                    ->orWhere('after_body', 'like', "%{$this->search}%")
                    ->orWhere('name', 'like', "%{$this->search}%");
            }));
    }
}
