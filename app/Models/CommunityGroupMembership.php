<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityGroupMembership extends Model
{
    protected $fillable = [
        'community_group_id',
        'user_id',
        'role',
        'joined_at',
        'last_read_on',
        'current_reading_streak',
        'longest_reading_streak',
        'completed_chapters_count',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
            'last_read_on' => 'date',
            'current_reading_streak' => 'integer',
            'longest_reading_streak' => 'integer',
            'completed_chapters_count' => 'integer',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(CommunityGroup::class, 'community_group_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
