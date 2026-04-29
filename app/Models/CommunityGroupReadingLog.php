<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityGroupReadingLog extends Model
{
    protected $fillable = [
        'community_group_id',
        'community_group_reading_challenge_id',
        'user_id',
        'bible_book_id',
        'chapter',
        'read_on',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'chapter' => 'integer',
            'read_on' => 'date',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(CommunityGroup::class, 'community_group_id');
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(CommunityGroupReadingChallenge::class, 'community_group_reading_challenge_id');
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
