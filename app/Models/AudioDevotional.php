<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudioDevotional extends Model
{
    use HasFactory;

    protected $fillable = [
        'devotional_id',
        'user_id',
        'title',
        'slug',
        'description',
        'audio_url',
        'duration_seconds',
        'speaker',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function devotional(): BelongsTo
    {
        return $this->belongsTo(Devotional::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getDurationLabelAttribute(): string
    {
        if (! $this->duration_seconds) {
            return 'Audio';
        }

        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
