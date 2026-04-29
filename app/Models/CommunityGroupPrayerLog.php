<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityGroupPrayerLog extends Model
{
    protected $fillable = [
        'community_group_prayer_id',
        'user_id',
        'prayed_on',
    ];

    protected function casts(): array
    {
        return [
            'prayed_on' => 'date',
        ];
    }

    public function prayer(): BelongsTo
    {
        return $this->belongsTo(CommunityGroupPrayer::class, 'community_group_prayer_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
