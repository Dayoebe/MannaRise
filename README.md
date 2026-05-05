# MannaRise

MannaRise is a Laravel and Livewire devotional platform for daily spiritual growth. It brings devotionals, Bible reading, prayer rooms, prayer requests, testimonies, journaling, audio devotionals, memory verses, devotional plans, reminders, community groups, and admin moderation into one focused product.

## Core Features

- Daily devotional experience with featured and latest devotionals
- Bible reader and spiritual library
- Prayer wall, guided prayer, and prayer rooms
- Testimony submission and moderation
- Personal journal, favorites, personalized reminders, missed-day nudges, weekly spiritual digest, and growth path
- Memory verses, scripture cards, and devotional plans
- Community groups and group invitations
- Admin dashboard for content, moderation, engagement, scheduled mail delivery, notification logs, roles, settings, and audio devotionals
- PWA-ready layout with manifest and app icons

## Tech Stack

- Laravel 12
- Livewire 4
- PHP 8.2+
- Tailwind CSS 4
- Vite
- MySQL or compatible database

## Documentation

- [Architecture](docs/ARCHITECTURE.md)
- [Database plan](docs/DATABASE.md)
- [Roadmap](docs/ROADMAP.md)
- [Local setup](docs/SETUP.md)
- [Development plan](PROJECT_PLAN.md)

## Local Installation

Clone the repository:

```bash
git clone https://github.com/Dayoebe/MannaRise.git
cd MannaRise
```

Install PHP dependencies:

```bash
composer install
```

Install JavaScript dependencies:

```bash
npm install
```

Create your environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Update your database credentials in `.env`, then run migrations and seeders:

```bash
php artisan migrate --seed
```

Build frontend assets:

```bash
npm run build
```

Start the local server:

```bash
php artisan serve
```

## Optional Seed Admin

For security, the database seeder does not create a public hardcoded admin account. To create a seeded admin during local setup, add these values to your private `.env` file before running the seeder:

```env
MANNA_SEED_ADMIN_NAME="MannaRise Admin"
MANNA_SEED_ADMIN_EMAIL="admin@example.com"
MANNA_SEED_ADMIN_PASSWORD="change-this-password"
```

Never commit real admin credentials into the repository.

## Useful Local Routes

- `/daily`
- `/bible`
- `/devotionals`
- `/library`
- `/prayer-rooms`
- `/dashboard`
- `/admin`

## Testing

Run the test suite with:

```bash
php artisan test
```

## Production Notes

- Set `APP_ENV=production`.
- Set `APP_DEBUG=false`.
- Use a strong `APP_KEY`.
- Configure a real database, mail driver, queue driver, and cache driver.
- Run `composer install --no-dev --optimize-autoloader`.
- Run `npm run build` before deployment.
- Run `php artisan migrate --force` during deployment.
- Make sure the web server points to Laravel's `public` directory.
- Ensure `storage` and `bootstrap/cache` are writable.
- Do not seed demo users or public credentials in production.

## Useful Commands

```bash
php artisan optimize:clear
php artisan migrate --seed
php artisan test
npm run build
```
