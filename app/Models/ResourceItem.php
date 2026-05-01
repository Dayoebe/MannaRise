<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ResourceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_category_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'content',
        'type',
        'source_name',
        'source_url',
        'external_id',
        'author',
        'thumbnail_url',
        'media_url',
        'embed_url',
        'language',
        'license',
        'tags',
        'metadata',
        'is_featured',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'metadata' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ResourceCategory::class, 'resource_category_id');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(UserResourceBookmark::class);
    }

    public function progressRecords(): HasMany
    {
        return $this->hasMany(UserResourceProgress::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeType(Builder $query, ?string $type): Builder
    {
        return $type ? $query->where('type', $type) : $query;
    }

    public function readingMinutes(): int
    {
        $words = str_word_count(strip_tags($this->content ?: $this->description ?: $this->excerpt ?: ''));

        return max(1, (int) ceil($words / 220));
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'resource';
        $slug = $base;
        $suffix = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
