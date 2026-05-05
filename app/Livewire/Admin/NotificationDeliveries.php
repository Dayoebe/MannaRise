<?php

namespace App\Livewire\Admin;

use App\Models\DevotionalReminder;
use App\Models\NotificationDeliveryLog;
use App\Support\NotificationDelivery;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationDeliveries extends Component
{
    use WithPagination;

    public function sendDaily(int $reminderId): void
    {
        $reminder = DevotionalReminder::with('user')->findOrFail($reminderId);
        $result = NotificationDelivery::sendDaily($reminder, mailOnly: true);

        session()->flash($result['sent'] ? 'status' : 'error', $result['message']);
    }

    public function sendWeeklyDigest(int $reminderId): void
    {
        $reminder = DevotionalReminder::with('user')->findOrFail($reminderId);
        $result = NotificationDelivery::sendWeeklyDigest($reminder, mailOnly: true);

        session()->flash($result['sent'] ? 'status' : 'error', $result['message']);
    }

    public function render()
    {
        $reminders = DevotionalReminder::with('user')
            ->where('is_active', true)
            ->latest()
            ->get();

        return view('livewire.admin.notification-deliveries', [
            'sentLogs' => NotificationDeliveryLog::with('user')
                ->where('channel', 'mail')
                ->latest()
                ->paginate(15),
            'scheduledDaily' => $this->scheduledDaily($reminders),
            'scheduledWeekly' => $this->scheduledWeekly($reminders),
            'optedOutCount' => DevotionalReminder::where('email_enabled', false)->count(),
            'mailEnabledCount' => DevotionalReminder::where('email_enabled', true)->count(),
            'failedCount' => NotificationDeliveryLog::where('channel', 'mail')->where('status', 'failed')->count(),
        ]);
    }

    private function scheduledDaily(Collection $reminders): Collection
    {
        return $reminders
            ->filter(fn (DevotionalReminder $reminder) => $reminder->email_enabled && $reminder->is_active && $reminder->user && ! $reminder->last_sent_at?->isToday())
            ->values();
    }

    private function scheduledWeekly(Collection $reminders): Collection
    {
        return $reminders
            ->filter(fn (DevotionalReminder $reminder) => $reminder->email_enabled && $reminder->is_active && $reminder->user && in_array('digest', $reminder->days['types'] ?? [], true))
            ->values();
    }
}
