<?php

namespace Database\Seeders;

use App\Models\Devotional;
use App\Models\DevotionalCategory;
use App\Models\DevotionalCompletion;
use App\Models\DevotionalFavorite;
use App\Models\JournalEntry;
use App\Models\PrayerRequest;
use App\Models\Testimony;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@mannarise.test'],
            [
                'name' => 'MannaRise Admin',
                'password' => 'password',
                'is_admin' => true,
            ],
        );

        $reader = User::updateOrCreate(
            ['email' => 'reader@mannarise.test'],
            [
                'name' => 'MannaRise Reader',
                'password' => 'password',
                'is_admin' => false,
            ],
        );

        $categories = collect([
            ['name' => 'Faith', 'description' => 'Trusting God with daily obedience.'],
            ['name' => 'Prayer', 'description' => 'Growing in honest conversation with God.'],
            ['name' => 'Purpose', 'description' => 'Walking with clarity and courage.'],
            ['name' => 'Healing', 'description' => 'Receiving restoration and hope.'],
            ['name' => 'Family', 'description' => 'Building homes marked by grace.'],
            ['name' => 'Spiritual Growth', 'description' => 'Maturity through Scripture and practice.'],
        ])->mapWithKeys(function (array $category) {
            $model = DevotionalCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    ...$category,
                    'slug' => Str::slug($category['name']),
                    'is_active' => true,
                ],
            );

            return [$model->slug => $model];
        });

        $devotionals = [
            [
                'category' => 'faith',
                'title' => 'Faith for Today',
                'bible_reference' => 'Hebrews 11:1',
                'bible_text' => 'Now faith is confidence in what we hope for and assurance about what we do not see.',
                'content' => "Faith is not denial of what stands in front of you. Faith is choosing to trust God's character while you take the next obedient step.\n\nToday, name the place where fear has been louder than promise. Bring it into prayer, then take one concrete action that agrees with what God has said.",
                'reflection_question' => 'Where do I need to obey before I feel fully certain?',
                'prayer_point' => 'Father, strengthen my trust and steady my steps today.',
                'declaration' => 'I walk by faith and not by fear.',
                'is_featured' => true,
            ],
            [
                'category' => 'prayer',
                'title' => 'A Quiet Place to Pray',
                'bible_reference' => 'Matthew 6:6',
                'bible_text' => 'When you pray, go into your room, close the door and pray to your Father, who is unseen.',
                'content' => "Prayer becomes deeper when it becomes honest. God is not asking for polished language; He invites your whole heart.\n\nSet aside a quiet moment today. Speak plainly. Listen patiently. Let prayer become communion, not performance.",
                'reflection_question' => 'What have I avoided saying honestly to God?',
                'prayer_point' => 'Lord, teach me to pray with honesty and trust.',
                'declaration' => 'My Father hears me when I pray.',
                'is_featured' => false,
            ],
            [
                'category' => 'purpose',
                'title' => 'Called to Fruitfulness',
                'bible_reference' => 'John 15:5',
                'bible_text' => 'If you remain in me and I in you, you will bear much fruit; apart from me you can do nothing.',
                'content' => "Purpose is not sustained by pressure. It is sustained by abiding. The fruit God desires grows from connection with Him.\n\nBefore you chase productivity today, return to the Source. Ask what faithfulness looks like in this season, then let obedience define success.",
                'reflection_question' => 'Where am I striving instead of abiding?',
                'prayer_point' => 'Jesus, keep me connected to You in my work and decisions.',
                'declaration' => 'As I abide in Christ, my life bears lasting fruit.',
                'is_featured' => false,
            ],
        ];

        foreach ($devotionals as $index => $data) {
            Devotional::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'devotional_category_id' => $categories[$data['category']]->id,
                    'user_id' => $admin->id,
                    'title' => $data['title'],
                    'slug' => Str::slug($data['title']),
                    'bible_reference' => $data['bible_reference'],
                    'bible_text' => $data['bible_text'],
                    'content' => $data['content'],
                    'reflection_question' => $data['reflection_question'],
                    'prayer_point' => $data['prayer_point'],
                    'declaration' => $data['declaration'],
                    'published_at' => now()->subDays(3 - $index),
                    'is_featured' => $data['is_featured'],
                    'is_published' => true,
                    'reading_time' => 4,
                ],
            );
        }

        $firstDevotional = Devotional::where('slug', 'faith-for-today')->first();

        if ($firstDevotional) {
            DevotionalFavorite::firstOrCreate([
                'user_id' => $reader->id,
                'devotional_id' => $firstDevotional->id,
            ]);

            DevotionalCompletion::firstOrCreate([
                'user_id' => $reader->id,
                'devotional_id' => $firstDevotional->id,
                'completed_on' => today()->toDateString(),
            ]);

            JournalEntry::updateOrCreate(
                ['user_id' => $reader->id, 'title' => 'Choosing faith today'],
                [
                    'devotional_id' => $firstDevotional->id,
                    'content' => 'I am learning to take the next obedient step without needing every answer first.',
                    'entry_date' => today()->toDateString(),
                ],
            );
        }

        PrayerRequest::updateOrCreate(
            ['title' => 'Wisdom for a family decision'],
            [
                'user_id' => $reader->id,
                'name' => $reader->name,
                'email' => $reader->email,
                'body' => 'Please pray for clarity, unity, and peace as our family makes an important decision.',
                'is_public' => true,
                'is_answered' => false,
                'prayed_count' => 0,
            ],
        );

        Testimony::updateOrCreate(
            ['title' => 'Peace in a hard week'],
            [
                'user_id' => $reader->id,
                'name' => $reader->name,
                'body' => 'The devotional on prayer helped me pause and bring my anxiety to God instead of carrying it alone.',
                'is_anonymous' => false,
                'is_approved' => true,
            ],
        );

        $this->call(MannaRiseContentSeeder::class);
    }
}
