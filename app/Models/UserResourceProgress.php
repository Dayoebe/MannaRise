<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserResourceProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resource_item_id',
        'progress_type',
        'progress_value',
        'completed_at',
        'last_accessed_at',
    ];

    protected function casts(): array
    {
        return [
            'progress_value' => 'integer',
            'completed_at' => 'datetime',
            'last_accessed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(ResourceItem::class, 'resource_item_id');
    }
}
