# Local Setup

These steps are for Debian or any Linux environment with PHP, Composer, Node, NPM, and MySQL available.

## Clone

Clone the repository and enter the project directory.

## Bootstrap

Run the bootstrap script from the repository root.

`bash scripts/bootstrap-mannarise.sh`

## Environment

Update `.env` with your local database details.

Recommended database name: `mannarise`

## Database

Create the database in your preferred MySQL tool, then run migrations.

`php artisan migrate --seed`

Seeded local accounts:

- Admin: `admin@mannarise.test` / `password`
- Reader: `reader@mannarise.test` / `password`

## Testing

Create a separate MySQL database for the test suite.

`mysql -h127.0.0.1 -uroot -e "CREATE DATABASE IF NOT EXISTS mannarise_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"`

Run the tests.

`php artisan test`

## Development Server

Run the frontend watcher and Laravel server in separate terminals.

`npm run dev`

`php artisan serve`

## Dashboard Direction

This project uses pure Livewire dashboards. Do not install Filament for the MVP.
