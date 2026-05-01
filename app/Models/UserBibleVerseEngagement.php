<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBibleVerseEngagement extends Model
{
    protected $fillable = [
        'user_id',
        'bible_verse_id',
        'highlight_color',
        'note',
        'bookmarked_at',
        'highlighted_at',
        'note_updated_at',
        'shared_at',
    ];

    protected function casts(): array
    {
        return [
            'bookmarked_at' => 'datetime',
            'highlighted_at' => 'datetime',
            'note_updated_at' => 'datetime',
            'shared_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verse(): BelongsTo
    {
        return $this->belongsTo(BibleVerse::class, 'bible_verse_id');
    }
}
