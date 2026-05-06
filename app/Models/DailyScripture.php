<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyScripture extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'reference',
        'book',
        'chapter',
        'verse',
        'translation',
        'text',
        'response_payload',
        'verse_date',
        'is_active',
        'fetched_at',
    ];

    protected function casts(): array
    {
        return [
            'chapter' => 'integer',
            'response_payload' => 'array',
            'verse_date' => 'date',
            'is_active' => 'boolean',
            'fetched_at' => 'datetime',
        ];
    }

    public function scopeForToday(Builder $query): Builder
    {
        return $query->whereDate('verse_date', today());
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function bibleRouteParameters(): ?array
    {
        $book = BibleBook::query()
            ->where('name', $this->book)
            ->orWhere('slug', str($this->book)->slug())
            ->first();

        if (! $book || ! $this->chapter) {
            return null;
        }

        return ['book' => $book->slug, 'chapter' => $this->chapter];
    }
}
