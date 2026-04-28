<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrayerRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'scripture_reference',
        'accent',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public static function defaults(): array
    {
        return [
            [
                'name' => 'Healing',
                'slug' => 'healing',
                'description' => 'Stand with people believing God for strength, recovery, peace, and wise medical care.',
                'scripture_reference' => 'Jeremiah 30:17',
                'accent' => 'rose',
                'sort_order' => 10,
            ],
            [
                'name' => 'Family',
                'slug' => 'family',
                'description' => 'Pray over homes, children, parents, reconciliation, protection, and daily love.',
                'scripture_reference' => 'Joshua 24:15',
                'accent' => 'emerald',
                'sort_order' => 20,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Cover work, provision, ethical decisions, open doors, teams, and financial stewardship.',
                'scripture_reference' => 'Proverbs 16:3',
                'accent' => 'amber',
                'sort_order' => 30,
            ],
            [
                'name' => 'Exams',
                'slug' => 'exams',
                'description' => 'Support students preparing for exams, interviews, certifications, focus, and courage.',
                'scripture_reference' => 'James 1:5',
                'accent' => 'sky',
                'sort_order' => 40,
            ],
            [
                'name' => 'Marriage',
                'slug' => 'marriage',
                'description' => 'Pray for marriages, engaged couples, restoration, communication, patience, and unity.',
                'scripture_reference' => 'Ecclesiastes 4:12',
                'accent' => 'fuchsia',
                'sort_order' => 50,
            ],
            [
                'name' => 'Salvation',
                'slug' => 'salvation',
                'description' => 'Intercede for people to know Jesus, return to faith, and find a local spiritual family.',
                'scripture_reference' => 'Romans 10:13',
                'accent' => 'indigo',
                'sort_order' => 60,
            ],
        ];
    }

    public static function syncDefaults(): void
    {
        foreach (self::defaults() as $room) {
            self::query()->updateOrCreate(
                ['slug' => $room['slug']],
                [...$room, 'is_active' => true],
            );
        }
    }

    public function requests(): HasMany
    {
        return $this->hasMany(PrayerRequest::class);
    }

    public function publicRequests(): HasMany
    {
        return $this->requests()->where('is_public', true);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(PrayerRoomMembership::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'prayer_room_memberships')
            ->withPivot(['joined_at', 'last_prayed_on', 'current_streak', 'longest_streak', 'total_prayers'])
            ->withTimestamps();
    }

    public function prayers(): HasMany
    {
        return $this->hasMany(PrayerRoomPrayer::class);
    }
}
