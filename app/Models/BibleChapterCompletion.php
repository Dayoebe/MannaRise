<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BibleChapterCompletion extends Model
{
    protected $fillable = [
        'user_id',
        'bible_book_id',
        'chapter',
        'assigned_on',
        'source',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'chapter' => 'integer',
            'assigned_on' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(BibleBook::class, 'bible_book_id');
    }
}
