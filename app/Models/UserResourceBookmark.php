<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserResourceBookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resource_item_id',
        'daily_devotion_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(ResourceItem::class, 'resource_item_id');
    }

    public function devotion(): BelongsTo
    {
        return $this->belongsTo(DailyDevotion::class, 'daily_devotion_id');
    }
}
