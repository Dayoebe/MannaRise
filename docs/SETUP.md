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

`php artisan migrate`

## Development Server

Run the frontend watcher and Laravel server in separate terminals.

`npm run dev`

`php artisan serve`

## Dashboard Direction

This project uses pure Livewire dashboards. Do not install Filament for the MVP.
