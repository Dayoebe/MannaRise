<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrayerRoomMembership extends Model
{
    protected $fillable = [
        'user_id',
        'prayer_room_id',
        'joined_at',
        'last_prayed_on',
        'current_streak',
        'longest_streak',
        'total_prayers',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
            'last_prayed_on' => 'date',
            'current_streak' => 'integer',
            'longest_streak' => 'integer',
            'total_prayers' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(PrayerRoom::class, 'prayer_room_id');
    }
}
