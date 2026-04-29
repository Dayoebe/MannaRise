<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeaturedContentSlot extends Model
{
    protected $fillable = [
        'slot_key',
        'devotional_id',
        'label',
        'description',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function defaults(): array
    {
        return [
            'home_featured' => [
                'label' => 'Home featured reading',
                'description' => 'Primary devotional shown in the home page featured reading panel.',
            ],
            'daily_featured' => [
                'label' => 'Daily page devotional',
                'description' => 'Devotional promoted alongside the daily rhythm page.',
            ],
            'app_spotlight' => [
                'label' => 'App section spotlight',
                'description' => 'Reusable featured devotional for app-like sections and compact highlights.',
            ],
        ];
    }

    public static function syncDefaults(): void
    {
        foreach (self::defaults() as $slotKey => $definition) {
            self::firstOrCreate(
                ['slot_key' => $slotKey],
                [
                    'label' => $definition['label'],
                    'description' => $definition['description'],
                    'is_active' => true,
                ],
            );
        }
    }

    public static function activeDevotionalFor(string $slotKey): ?Devotional
    {
        $slot = self::query()
            ->active()
            ->where('slot_key', $slotKey)
            ->first();

        if (! $slot?->devotional_id) {
            return null;
        }

        return Devotional::query()
            ->with('category')
            ->published()
            ->find($slot->devotional_id);
    }

    public function devotional(): BelongsTo
    {
        return $this->belongsTo(Devotional::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(function (Builder $query): void {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $query): void {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function statusLabel(): string
    {
        if (! $this->is_active) {
            return 'Paused';
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return 'Scheduled';
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return 'Expired';
        }

        return $this->devotional_id ? 'Live' : 'Empty';
    }
}
