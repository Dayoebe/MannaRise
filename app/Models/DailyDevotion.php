<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DailyDevotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'bible_reference',
        'bible_text',
        'memory_verse',
        'devotion_text',
        'prayer',
        'reflection_questions',
        'action_point',
        'devotion_date',
        'author',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'reflection_questions' => 'array',
            'devotion_date' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(UserResourceBookmark::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'daily-devotion';
        $slug = $base;
        $suffix = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
