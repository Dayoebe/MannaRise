<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityGroupReadingChallenge extends Model
{
    protected $fillable = [
        'community_group_id',
        'title',
        'description',
        'starts_on',
        'ends_on',
        'daily_chapter_goal',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'daily_chapter_goal' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(CommunityGroup::class, 'community_group_id');
    }

    public function readingLogs(): HasMany
    {
        return $this->hasMany(CommunityGroupReadingLog::class);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where('starts_on', '<=', today())
            ->where(function (Builder $query): void {
                $query->whereNull('ends_on')->orWhere('ends_on', '>=', today());
            });
    }
}
