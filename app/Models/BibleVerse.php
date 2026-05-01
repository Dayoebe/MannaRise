<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BibleVerse extends Model
{
    use HasFactory;

    protected $fillable = [
        'bible_book_id',
        'language',
        'version',
        'chapter',
        'verse',
        'text',
    ];

    protected function casts(): array
    {
        return [
            'chapter' => 'integer',
            'verse' => 'integer',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(BibleBook::class, 'bible_book_id');
    }
}
