<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityGroupPrayer extends Model
{
    protected $fillable = [
        'community_group_id',
        'user_id',
        'title',
        'body',
        'is_answered',
        'prayed_count',
    ];

    protected function casts(): array
    {
        return [
            'is_answered' => 'boolean',
            'prayed_count' => 'integer',
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

    public function prayerLogs(): HasMany
    {
        return $this->hasMany(CommunityGroupPrayerLog::class);
    }
}
