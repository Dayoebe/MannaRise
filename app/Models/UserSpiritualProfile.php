<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSpiritualProfile extends Model
{
    protected $fillable = [
        'user_id',
        'season',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
