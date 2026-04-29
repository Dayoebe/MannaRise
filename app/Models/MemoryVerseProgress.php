<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemoryVerseProgress extends Model
{
    use HasFactory;

    protected $table = 'memory_verse_progress';

    protected $fillable = [
        'user_id',
        'week_start',
        'bible_verse_id',
        'reference',
        'verse_text',
        'practiced_count',
        'reminder_enabled',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'week_start' => 'date',
            'practiced_count' => 'integer',
            'reminder_enabled' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bibleVerse(): BelongsTo
    {
        return $this->belongsTo(BibleVerse::class);
    }
}
