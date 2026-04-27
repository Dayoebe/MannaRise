<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpiritualBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author',
        'tradition',
        'source',
        'published_year',
        'description',
        'is_public_domain',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'published_year' => 'integer',
            'is_public_domain' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(SpiritualBookChapter::class)->orderBy('chapter_number');
    }
}
