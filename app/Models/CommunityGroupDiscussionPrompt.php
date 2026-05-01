<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityGroupDiscussionPrompt extends Model
{
    protected $fillable = [
        'community_group_id',
        'created_by',
        'week_start',
        'title',
        'prompt',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'week_start' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(CommunityGroup::class, 'community_group_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
