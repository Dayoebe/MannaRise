<?php

namespace App\Support;

use App\Models\User;
use App\Notifications\ActivityAlert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Throwable;

class ActivityAlerts
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public static function notify(?User $user, string $type, string $title, string $message, ?string $url = null, string $icon = 'bell', array $meta = []): void
    {
        if (! $user) {
            return;
        }

        try {
            $user->notify(new ActivityAlert($type, $title, $message, $url, $icon, $meta));
        } catch (Throwable) {
            report(new \RuntimeException('Unable to create in-app activity alert for '.$type));
        }
    }

    /**
     * @param  array<string, mixed>  $meta
     */
    public static function notifyAdmins(string $type, string $title, string $message, ?string $url = null, string $icon = 'shield', array $meta = []): void
    {
        self::adminUsers()->each(fn (User $user) => self::notify($user, $type, $title, $message, $url, $icon, $meta));
    }

    /**
     * @param  array<string, mixed>  $meta
     */
    public static function notifyOwner(Model $model, string $type, string $title, string $message, ?string $url = null, string $icon = 'bell', array $meta = []): void
    {
        $user = method_exists($model, 'user') ? $model->user : null;

        self::notify($user instanceof User ? $user : null, $type, $title, $message, $url, $icon, $meta + [
            'model' => class_basename($model),
            'model_id' => $model->getKey(),
        ]);
    }

    /**
     * @return Collection<int, User>
     */
    private static function adminUsers(): Collection
    {
        return User::query()
            ->where(fn ($query) => $query->where('is_admin', true)->orWhere('is_super_admin', true))
            ->get();
    }
}
