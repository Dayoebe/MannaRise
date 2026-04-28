<?php

namespace Database\Seeders;

use App\Models\Devotional;
use App\Models\DevotionalCategory;
use App\Models\DevotionalCompletion;
use App\Models\DevotionalFavorite;
use App\Models\JournalEntry;
use App\Models\PrayerRequest;
use App\Models\PrayerRequestUpdate;
use App\Models\PrayerRoom;
use App\Models\Testimony;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MannaRiseContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@mannarise.test'],
            ['name' => 'MannaRise Admin', 'password' => 'password', 'is_admin' => true, 'is_super_admin' => false],
        );

        User::updateOrCreate(
            ['email' => 'super@admin.com'],
            ['name' => 'MannaRise Super Admin', 'password' => '9638', 'is_admin' => true, 'is_super_admin' => true],
        );

        $readers = collect([
            ['name' => 'Ada Grace', 'email' => 'ada@mannarise.test'],
            ['name' => 'Daniel Hope', 'email' => 'daniel@mannarise.test'],
            ['name' => 'Miriam Joy', 'email' => 'miriam@mannarise.test'],
            ['name' => 'Samuel Light', 'email' => 'samuel@mannarise.test'],
            ['name' => 'Ruth Peace', 'email' => 'ruth@mannarise.test'],
        ])->map(fn (array $reader) => User::updateOrCreate(
            ['email' => $reader['email']],
            ['name' => $reader['name'], 'password' => 'password', 'is_admin' => false, 'is_super_admin' => false],
        ));

        PrayerRoom::syncDefaults();
        $prayerRooms = PrayerRoom::pluck('id', 'slug');

        $categories = collect([
            ['name' => 'Faith', 'description' => 'Trusting God with daily obedience.'],
            ['name' => 'Prayer', 'description' => 'Growing in honest conversation with God.'],
            ['name' => 'Purpose', 'description' => 'Walking with clarity and courage.'],
            ['name' => 'Healing', 'description' => 'Receiving restoration and hope.'],
            ['name' => 'Family', 'description' => 'Building homes marked by grace.'],
            ['name' => 'Business', 'description' => 'Working with wisdom, service, and integrity.'],
            ['name' => 'Wisdom', 'description' => 'Practicing discernment in everyday decisions.'],
            ['name' => 'Hope', 'description' => 'Holding steady when the future feels uncertain.'],
            ['name' => 'Forgiveness', 'description' => 'Receiving mercy and releasing others.'],
            ['name' => 'Leadership', 'description' => 'Serving people with humility and courage.'],
            ['name' => 'Peace', 'description' => 'Resting in God through pressure and transition.'],
            ['name' => 'Spiritual Growth', 'description' => 'Maturity through Scripture and practice.'],
        ])->mapWithKeys(function (array $category) {
            $model = DevotionalCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                    'is_active' => true,
                ],
            );

            return [$model->slug => $model];
        });

        $makeDevotional = function (string $category, string $title, string $reference, string $theme, bool $featured = false): array {
            return [
                'category' => $category,
                'title' => $title,
                'bible_reference' => $reference,
                'bible_text' => "A devotional reflection drawn from {$reference}.",
                'content' => "{$theme}\n\nLet this truth become practical today. Pause before rushing, listen for the Spirit's correction, and choose one concrete act of obedience. Growth often begins with a small faithful response that no one else sees.",
                'reflection_question' => 'What is one obedient response I can practice today?',
                'prayer_point' => 'Father, shape my heart and guide my steps as I respond to Your word.',
                'declaration' => 'I receive grace for today and walk forward with faith.',
                'is_featured' => $featured,
            ];
        };

        $devotionals = [
            $makeDevotional('faith', 'Strength for the Waiting Season', 'Psalm 27:14', 'Waiting can reveal whether our confidence is built on outcomes or on God Himself. Patience is not passive; it is trust that keeps worshiping while the answer is still forming.', true),
            $makeDevotional('faith', 'Courage When the Door Opens', 'Joshua 1:9', 'Open doors still require courage. God does not remove every unknown before He calls us forward, but He promises His presence in the movement.'),
            $makeDevotional('faith', 'Trust Beyond the Visible', '2 Corinthians 5:7', 'Faith trains the heart to see beyond immediate evidence. The visible facts matter, but they do not get the final word over what God has spoken.'),
            $makeDevotional('prayer', 'Morning Prayer Before Noise', 'Mark 1:35', 'The first words of the day can reorient the whole day. Before messages, demands, and decisions crowd your mind, give God the first conversation.'),
            $makeDevotional('prayer', 'Praying Through Distraction', 'Philippians 4:6', 'Distraction does not disqualify prayer. Bring the scattered thoughts one by one before God and let anxiety become an invitation to communion.'),
            $makeDevotional('prayer', 'Intercession With Compassion', '1 Timothy 2:1', 'Intercession forms love in us. As we carry names before God, our hearts become less critical and more willing to serve.'),
            $makeDevotional('purpose', 'Work With Eternal Weight', 'Colossians 3:23', 'Ordinary work becomes holy when it is offered to God. Excellence is not performance; it is stewardship of the assignment in your hands.'),
            $makeDevotional('purpose', 'Small Steps of Calling', 'Zechariah 4:10', 'Calling is often built through small, repeated obedience. Do not despise the quiet foundations God is laying.'),
            $makeDevotional('purpose', 'Faithful With Today', 'Matthew 25:23', 'Purpose grows where faithfulness is practiced. Today is not a waiting room for destiny; it is part of the assignment.'),
            $makeDevotional('healing', 'Grace for the Tender Place', 'Psalm 147:3', 'God does not rush wounded hearts. His healing is both gentle and strong, meeting pain without shame and restoring hope without denial.'),
            $makeDevotional('healing', 'Hope After Disappointment', 'Romans 15:13', 'Disappointment can narrow expectation, but God restores hope with His presence. You are not required to pretend; you are invited to trust again.'),
            $makeDevotional('healing', 'Rest for the Weary Soul', 'Matthew 11:28', 'Weariness is not failure. Jesus calls tired people close and teaches a way of life that does not crush the soul.'),
            $makeDevotional('family', 'Speaking Grace at Home', 'Ephesians 4:29', 'The words spoken at home shape the atmosphere of home. Grace-filled speech does not avoid truth; it carries truth with love.'),
            $makeDevotional('family', 'Patience With the People You Love', 'Colossians 3:13', 'Love is tested in daily nearness. Patience makes room for growth, apology, listening, and repair.'),
            $makeDevotional('family', 'Building a Peaceful Home', 'Psalm 127:1', 'A peaceful home is not built by control alone. It is formed by dependence on God, humble leadership, and consistent love.'),
            $makeDevotional('business', 'Integrity in the Hidden Places', 'Proverbs 11:3', 'Integrity is proven when no one is watching. God forms trustworthy people through honest choices in private and public work.'),
            $makeDevotional('business', 'Serving Through Your Work', '1 Peter 4:10', 'Business can become service when gifts are stewarded for people, not only profit. Ask who your work is meant to bless today.'),
            $makeDevotional('business', 'Wisdom for Decisions', 'James 1:5', 'Decision fatigue lifts when wisdom becomes prayer. God welcomes practical questions and gives guidance without contempt.'),
            $makeDevotional('wisdom', 'A Teachable Spirit', 'Proverbs 9:9', 'Wisdom grows in people who can still be corrected. A teachable heart receives instruction as grace, not insult.'),
            $makeDevotional('wisdom', 'Choosing the Better Response', 'Proverbs 15:1', 'A gentle response can interrupt cycles of anger. Wisdom is not only what you know; it is how you answer under pressure.'),
            $makeDevotional('wisdom', 'Discernment for Open Doors', 'Proverbs 3:5-6', 'Not every opportunity is an assignment. Discernment asks God for direction before momentum makes the decision.'),
            $makeDevotional('hope', 'Light in a Long Night', 'John 1:5', 'Hope does not deny darkness; it trusts that darkness cannot overcome the light of Christ.'),
            $makeDevotional('hope', 'When Tomorrow Feels Heavy', 'Lamentations 3:22-23', 'Mercy is not stored only for easy seasons. New mercies meet heavy mornings and give strength one day at a time.'),
            $makeDevotional('hope', 'Joy That Holds Steady', 'Nehemiah 8:10', 'Joy rooted in God can hold steady even when circumstances are unfinished. His strength is deeper than the day\'s pressure.'),
            $makeDevotional('forgiveness', 'Release the Debt', 'Matthew 6:14', 'Forgiveness is not pretending harm did not matter. It is entrusting justice to God and refusing to let bitterness govern the heart.'),
            $makeDevotional('forgiveness', 'Receiving Mercy Again', '1 John 1:9', 'Confession is not a doorway to rejection. In Christ, confession opens the heart to cleansing and restored fellowship.'),
            $makeDevotional('forgiveness', 'A Clean Heart', 'Psalm 51:10', 'God is able to renew motives, desires, and patterns. A clean heart begins with honest surrender.'),
            $makeDevotional('leadership', 'Lead by Serving', 'Mark 10:45', 'Kingdom leadership begins with service. Influence becomes healthy when it lifts burdens instead of collecting status.'),
            $makeDevotional('leadership', 'Courage to Decide', 'Esther 4:14', 'Leadership sometimes requires action before comfort arrives. Courage grows when responsibility is surrendered to God.'),
            $makeDevotional('leadership', 'Shepherd the Assignment', '1 Peter 5:2', 'People are not projects. Faithful leadership protects dignity, listens carefully, and serves with willing attention.'),
            $makeDevotional('peace', 'Peace in the Middle', 'Isaiah 26:3', 'God\'s peace is not limited to quiet circumstances. A stayed mind can experience steadiness even in a noisy season.'),
            $makeDevotional('peace', 'Breathing Room for the Soul', 'Psalm 46:10', 'Stillness is an act of trust. When you stop striving, you make room to remember that God is God.'),
            $makeDevotional('peace', 'Unhurried Obedience', 'Luke 10:42', 'Hurry can disguise itself as faithfulness. Jesus invites attention before activity and presence before performance.'),
            $makeDevotional('spiritual-growth', 'Rooted Before Fruitful', 'Psalm 1:3', 'Fruitfulness depends on roots. The unseen life with God sustains the visible life before people.'),
            $makeDevotional('spiritual-growth', 'Practice What You Hear', 'James 1:22', 'The word becomes formative when it is practiced. Listening is the beginning; obedience is where transformation deepens.'),
            $makeDevotional('spiritual-growth', 'Endurance in Formation', 'Galatians 6:9', 'Spiritual growth takes time. Do not quit the slow work of prayer, Scripture, confession, and love.'),
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
                    'published_at' => now()->subDays($index % 28)->setTime(7, 0),
                    'is_featured' => $data['is_featured'],
                    'is_published' => true,
                    'reading_time' => 4 + ($index % 4),
                ],
            );
        }

        $seriesThemes = [
            'faith' => ['Faith That Keeps Showing Up', 'Trust When Answers Delay', 'Boldness for the Next Step', 'Confidence in God\'s Character'],
            'prayer' => ['Prayer When Words Are Few', 'Listening After Amen', 'Praying for Your City', 'Consistency in the Secret Place'],
            'purpose' => ['Purpose in Ordinary Work', 'Surrendering Your Timeline', 'Clarity for the Next Assignment', 'Faithfulness Before Promotion'],
            'healing' => ['Healing From Hidden Wounds', 'Hope for the Brokenhearted', 'Letting God Restore Joy', 'Strength for Recovery'],
            'family' => ['Grace Around the Table', 'Covering Children in Prayer', 'Honoring Difficult Relatives', 'Choosing Peace at Home'],
            'business' => ['Building With Clean Hands', 'Profit With Purpose', 'Courage for Ethical Choices', 'Serving Customers Well'],
            'wisdom' => ['Wisdom for Hard Conversations', 'Discernment in Transition', 'Learning From Correction', 'Quietness Before Decisions'],
            'hope' => ['Hope for a New Beginning', 'Mercy for Heavy Mornings', 'Joy After Delay', 'Expectation Without Anxiety'],
            'forgiveness' => ['Forgiving Without Pretending', 'Mercy That Changes Memory', 'Leaving Vengeance With God', 'Receiving a Fresh Start'],
            'leadership' => ['Serving Before Speaking', 'Carrying Influence Humbly', 'Leading Through Pressure', 'Protecting What God Entrusted'],
            'peace' => ['Peace When Plans Change', 'A Stayed Mind', 'Rest in the Middle of Work', 'Silencing the Inner Storm'],
            'spiritual-growth' => ['Deep Roots in Scripture', 'Obedience After Conviction', 'Growing Through Accountability', 'Formation in Hidden Seasons'],
        ];

        $references = [
            'faith' => ['Romans 10:17', 'Mark 9:24', 'Psalm 56:3', 'Hebrews 10:23'],
            'prayer' => ['Romans 8:26', 'Jeremiah 33:3', 'Colossians 4:2', 'Psalm 5:3'],
            'purpose' => ['Ephesians 2:10', 'Psalm 37:5', 'Proverbs 16:3', 'Luke 16:10'],
            'healing' => ['Isaiah 61:1', 'Psalm 34:18', 'Jeremiah 30:17', 'Isaiah 40:29'],
            'family' => ['Joshua 24:15', 'Proverbs 22:6', 'Romans 12:18', 'Colossians 3:14'],
            'business' => ['Micah 6:8', 'Deuteronomy 8:18', 'Proverbs 16:11', 'Matthew 5:16'],
            'wisdom' => ['James 3:17', 'Ecclesiastes 3:1', 'Proverbs 12:1', 'Proverbs 18:13'],
            'hope' => ['Isaiah 43:19', 'Psalm 30:5', 'Romans 12:12', '1 Peter 1:3'],
            'forgiveness' => ['Ephesians 4:32', 'Luke 6:36', 'Romans 12:19', '2 Corinthians 5:17'],
            'leadership' => ['John 13:14', 'Philippians 2:3', '2 Timothy 1:7', 'Proverbs 27:23'],
            'peace' => ['John 14:27', 'Isaiah 26:3', 'Psalm 23:2', 'Mark 4:39'],
            'spiritual-growth' => ['Colossians 2:7', 'John 14:15', 'Proverbs 27:17', '2 Peter 3:18'],
        ];

        $seriesIndex = 0;

        foreach ($seriesThemes as $category => $titles) {
            foreach ($titles as $themeIndex => $title) {
                $reference = $references[$category][$themeIndex];

                Devotional::updateOrCreate(
                    ['slug' => Str::slug($title)],
                    [
                        'devotional_category_id' => $categories[$category]->id,
                        'user_id' => $admin->id,
                        'title' => $title,
                        'slug' => Str::slug($title),
                        'bible_reference' => $reference,
                        'bible_text' => "A devotional reflection drawn from {$reference}.",
                        'content' => "{$title} is an invitation to let God's word shape the way you think, speak, and respond today.\n\nRead the reference slowly. Notice what it reveals about God's character, then bring your current situation before Him without hiding or exaggerating. Choose one faithful action that agrees with the truth you have received.",
                        'reflection_question' => 'Where does this truth need to move from idea to practice in my life?',
                        'prayer_point' => 'Lord, make this word alive in me and help me obey with humility.',
                        'declaration' => 'My life is being formed by the word and presence of God.',
                        'published_at' => now()->subDays(($seriesIndex % 60) + 1)->setTime(6, 30),
                        'is_featured' => false,
                        'is_published' => true,
                        'reading_time' => 3 + ($seriesIndex % 5),
                    ],
                );

                $seriesIndex++;
            }
        }

        $prayers = [
            ['Healing for my mother', 'Please pray for strength, accurate diagnosis, and peace for our family while my mother receives treatment.', true, false, 34, 'healing'],
            ['Direction for a new job', 'I need wisdom about a job offer and courage to choose what honors God over fear.', true, false, 21, 'business'],
            ['Restoration in marriage', 'Please pray for humility, forgiveness, and honest communication in our marriage.', true, false, 48, 'marriage'],
            ['Peace during exams', 'Pray for focus, calm, and good recall as I prepare for final exams.', true, false, 15, 'exams'],
            ['Provision for rent', 'I am trusting God for provision and wise next steps after a difficult month.', true, false, 27, 'business'],
            ['Salvation for my brother', 'Please pray that my brother encounters Christ and finds a faithful community.', true, false, 42, 'salvation'],
            ['Strength after loss', 'Our family is grieving. Please pray for comfort and daily strength.', true, false, 39, 'healing'],
            ['Business wisdom', 'Pray for integrity, clarity, and provision as I rebuild my small business.', true, false, 18, 'business'],
            ['Answered: school admission', 'God opened a door for admission after months of waiting. Thank you for praying.', true, true, 66, 'exams'],
            ['Answered: safe delivery', 'We welcomed a healthy baby after a difficult pregnancy. We are grateful.', true, true, 73, 'family'],
            ['Answered: restored peace', 'A long family conflict softened after prayer and a hard conversation.', true, true, 58, 'family'],
            ['Private family matter', 'Please pray for wisdom and protection around a sensitive family concern.', false, false, 0, 'family'],
        ];

        foreach ($prayers as $index => [$title, $body, $isPublic, $isAnswered, $count, $roomSlug]) {
            $reader = $readers[$index % $readers->count()];

            $request = PrayerRequest::updateOrCreate(
                ['title' => $title],
                [
                    'user_id' => $reader->id,
                    'prayer_room_id' => $prayerRooms[$roomSlug] ?? null,
                    'name' => $reader->name,
                    'email' => $reader->email,
                    'body' => $body,
                    'is_public' => $isPublic,
                    'is_answered' => $isAnswered,
                    'prayed_count' => $count,
                    'created_at' => now()->subDays($index + 1),
                    'updated_at' => now()->subDays(max(0, $index - 1)),
                ],
            );

            if ($isAnswered) {
                PrayerRequestUpdate::updateOrCreate(
                    ['prayer_request_id' => $request->id, 'body' => $body],
                    [
                        'user_id' => $reader->id,
                        'is_answered_update' => true,
                    ],
                );
            }
        }

        $extraPrayerSubjects = [
            'Grace for a difficult workplace', 'Protection during travel', 'Wisdom for ministry planning', 'Healing from anxiety',
            'Provision for a new apartment', 'Strength for a caregiver', 'Breakthrough in studies', 'Peace after a medical report',
            'Unity in a church team', 'Direction for relocation', 'Courage to forgive', 'Growth in prayer discipline',
            'Rest for a tired pastor', 'Financial wisdom for a family', 'Hope while waiting for results', 'A child returning to faith',
            'Comfort after disappointment', 'Discipline for daily devotion', 'Favor in a legal process', 'Healing for a friendship',
        ];

        foreach ($extraPrayerSubjects as $index => $title) {
            $reader = $readers[$index % $readers->count()];
            $answered = $index % 6 === 0;
            $roomSlug = ['business', 'family', 'salvation', 'healing', 'business', 'family', 'exams', 'healing', 'family', 'business', 'marriage', 'salvation'][$index % 12];

            $request = PrayerRequest::updateOrCreate(
                ['title' => $title],
                [
                    'user_id' => $reader->id,
                    'prayer_room_id' => $prayerRooms[$roomSlug] ?? null,
                    'name' => $reader->name,
                    'email' => $reader->email,
                    'body' => $answered
                        ? 'This prayer has seen encouraging progress. Please join us in giving thanks and praying for continued strength.'
                        : 'Please stand with me in prayer for wisdom, peace, provision, and a heart that stays faithful through this season.',
                    'is_public' => true,
                    'is_answered' => $answered,
                    'prayed_count' => 8 + ($index * 3),
                    'created_at' => now()->subDays($index + 4),
                    'updated_at' => now()->subDays($index + 2),
                ],
            );

            if ($answered) {
                PrayerRequestUpdate::updateOrCreate(
                    ['prayer_request_id' => $request->id, 'body' => 'This prayer has seen encouraging progress. Thank you for praying with us.'],
                    [
                        'user_id' => $reader->id,
                        'is_answered_update' => true,
                    ],
                );
            }
        }

        $testimonies = [
            ['God met me in prayer', 'I started praying before checking my phone each morning, and my anxiety has been quieter. The change has been simple but deep.', false, true],
            ['A job came through', 'After weeks of applications and discouragement, God opened a role that fits my gifts and gives space for family.', false, true],
            ['Restored conversation', 'A devotional on forgiveness pushed me to make a call I had avoided. The conversation brought peace.', true, true],
            ['Healing after fear', 'The prayer wall reminded me I was not alone. I felt courage return while walking through treatment.', false, true],
            ['Provision at the right time', 'God provided through unexpected help exactly when rent was due. I am learning to trust His timing.', false, true],
            ['A new rhythm with Scripture', 'Reading daily devotionals helped me rebuild consistency after months of spiritual dryness.', false, true],
            ['Peace in parenting', 'The family devotionals helped me apologize faster and speak with more patience at home.', true, true],
            ['Clarity for business', 'I was about to rush into a deal, but prayer and counsel helped me slow down and choose wisely.', false, true],
            ['Pending testimony of healing', 'I am still walking through the process, but I can already see God strengthening my heart.', false, false],
            ['Pending testimony of reconciliation', 'A relationship is slowly being restored. I want to share more when the timing is right.', true, false],
        ];

        foreach ($testimonies as $index => [$title, $body, $anonymous, $approved]) {
            $reader = $readers[$index % $readers->count()];

            Testimony::updateOrCreate(
                ['title' => $title],
                [
                    'user_id' => $reader->id,
                    'name' => $reader->name,
                    'body' => $body,
                    'is_anonymous' => $anonymous,
                    'is_approved' => $approved,
                    'created_at' => now()->subDays($index + 2),
                    'updated_at' => now()->subDays($index + 1),
                ],
            );
        }

        $extraTestimonies = [
            'God restored my consistency', 'Peace before surgery', 'A reconciled friendship', 'Provision for school needs',
            'Freedom from bitterness', 'Renewed hunger for Scripture', 'Guidance in a business decision', 'Strength during grief',
            'Prayer became honest again', 'A family altar restarted', 'Courage to apologize', 'Hope after a failed plan',
            'A new job with better rhythm', 'Healing in my emotions', 'A testimony from the prayer wall', 'God met me in silence',
            'Financial discipline returned', 'A fresh start in leadership', 'Confidence to serve again', 'Joy in a waiting season',
        ];

        foreach ($extraTestimonies as $index => $title) {
            $reader = $readers[$index % $readers->count()];

            Testimony::updateOrCreate(
                ['title' => $title],
                [
                    'user_id' => $reader->id,
                    'name' => $reader->name,
                    'body' => 'This testimony began with a small step of faith. Through prayer, Scripture, and steady obedience, I saw God bring peace and practical help in a way I could not have arranged on my own.',
                    'is_anonymous' => $index % 5 === 0,
                    'is_approved' => $index % 7 !== 0,
                    'created_at' => now()->subDays($index + 6),
                    'updated_at' => now()->subDays($index + 3),
                ],
            );
        }

        $publishedDevotionals = Devotional::published()->orderBy('published_at')->take(18)->get();

        foreach ($readers as $readerIndex => $reader) {
            foreach ($publishedDevotionals->slice($readerIndex, 8) as $offset => $devotional) {
                DevotionalFavorite::firstOrCreate([
                    'user_id' => $reader->id,
                    'devotional_id' => $devotional->id,
                ]);

                DevotionalCompletion::firstOrCreate([
                    'user_id' => $reader->id,
                    'devotional_id' => $devotional->id,
                    'completed_on' => now()->subDays($offset)->toDateString(),
                ]);
            }

            $journalDevotional = $publishedDevotionals->get($readerIndex);

            if ($journalDevotional) {
                JournalEntry::updateOrCreate(
                    ['user_id' => $reader->id, 'title' => 'Reflection on '.$journalDevotional->title],
                    [
                        'devotional_id' => $journalDevotional->id,
                        'content' => 'This reading gave me a practical next step: slow down, pray honestly, and obey what God has already made clear.',
                        'entry_date' => now()->subDays($readerIndex)->toDateString(),
                    ],
                );
            }
        }
    }
}
