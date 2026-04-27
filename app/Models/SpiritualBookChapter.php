<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpiritualBookChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'spiritual_book_id',
        'chapter_number',
        'title',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'chapter_number' => 'integer',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(SpiritualBook::class, 'spiritual_book_id');
    }
}
