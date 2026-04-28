# Local Setup

These steps are for Debian or any Linux environment with PHP, Composer, Node, NPM, and MySQL available.

## Clone

Clone the repository and enter the project directory.

## Bootstrap

Run the bootstrap script from the repository root.

`bash scripts/bootstrap-mannarise.sh`

## Environment

Update `.env` with your local database details.

Recommended database name: `mannarise`.

## Database

Create the database in your preferred MySQL tool, then run migrations.

`php artisan migrate --seed`

Seeded local accounts:

- Admin: `admin@mannarise.test` / `password`
- Reader: `reader@mannarise.test` / `password`

Seeded data covers devotional categories, published devotionals, prayer rooms, public prayer wall entries, approved testimonies, favorites, journal entries, completion history, Bible reader records, and the public-domain spiritual library.

## Bible Import

The preferred development setup is the full public-domain KJV import. If an offline-safe seeder is available in the checkout, it will try local files before the remote source.

Preferred local file names:

- `database/seeders/data/kjv-verses.json`
- `database/seeders/data/verses-1769.json`
- `storage/app/private/kjv-verses.json`
- `storage/app/kjv-verses.json`

When no local file is available, the seeder may try the remote public-domain KJV JSON source. If your checkout does not include the offline fallback, the first full Bible import needs network access.

## Testing

Create a separate MySQL database for the test suite.

`mysql -h127.0.0.1 -uroot -e "CREATE DATABASE IF NOT EXISTS mannarise_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"`

Run the tests.

`php artisan test`

## Development Server

Run the frontend watcher and Laravel server in separate terminals.

`npm run dev`

`php artisan serve`

Core development routes:

- `/daily`
- `/bible`
- `/library`
- `/prayer-rooms`
- `/devotionals`
- `/dashboard`
- `/journal`
- `/favorites`
- `/admin`

Expanded or partial feature routes, when enabled in the checkout:

- `/audio-devotionals`
- `/reminders`

## XAMPP

When the project is served through XAMPP under `htdocs`, the public URL may look like:

`http://127.0.0.1/MannaRise/public`

## Dashboard Direction

This project uses pure Livewire dashboards. Do not install Filament for the MVP.
