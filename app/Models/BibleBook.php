<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BibleBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_order',
        'name',
        'slug',
        'abbreviation',
        'testament',
        'chapters',
    ];

    protected function casts(): array
    {
        return [
            'book_order' => 'integer',
            'chapters' => 'integer',
        ];
    }

    public function verses(): HasMany
    {
        return $this->hasMany(BibleVerse::class);
    }
}
