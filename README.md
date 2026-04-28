# MannaRise

MannaRise is a Laravel and Livewire devotional platform for daily spiritual growth. It brings together daily devotionals, a KJV Bible reader, verse of the day, daily affirmations, a Bible-in-a-year challenge, prayer journaling, prayer requests, testimonies, favorites, streaks, reminders, and a public-domain spiritual reading library.

## Current Experience

- Public home page, devotional listing, devotional detail pages, prayer wall, testimony pages, Bible reader, library reader, and daily rhythm page
- Daily rhythm with verse of the day, affirmation, and Bible-in-a-year reading assignment
- User dashboard with favorites, journal entries, prayer requests, completion counts, streaks, and daily rhythm prompts
- Custom admin control center with quick content input, settings, categories, devotionals, prayer requests, testimonies, and engagement
- Seeded KJV Bible data and public-domain spiritual library content
- Planned and partially built expansion areas for reminders, audio devotionals, role permissions, PWA support, devotional plans, social sharing, ministry spaces, donations, and richer analytics

## Documentation

- [Architecture](docs/ARCHITECTURE.md)
- [Database plan](docs/DATABASE.md)
- [Roadmap](docs/ROADMAP.md)
- [Local setup](docs/SETUP.md)
- [Development plan](PROJECT_PLAN.md)

## Local Access

After running migrations with seed data, use:

- Admin: `admin@mannarise.test` / `password`
- Reader: `reader@mannarise.test` / `password`

Useful local routes:

- `/daily`
- `/bible`
- `/devotionals`
- `/library`
- `/dashboard`
- `/admin`
