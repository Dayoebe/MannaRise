<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBibleReadingHistory extends Model
{
    protected $fillable = [
        'user_id',
        'bible_book_id',
        'chapter',
        'language',
        'version',
        'read_count',
        'last_read_at',
    ];

    protected function casts(): array
    {
        return [
            'chapter' => 'integer',
            'read_count' => 'integer',
            'last_read_at' => 'datetime',
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
