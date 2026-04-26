#!/usr/bin/env bash

set -e

APP_NAME="MannaRise"
TEMP_DIR="temp-mannarise-app"

if [ -f "artisan" ]; then
    echo "Laravel already appears to be installed in this directory."
    exit 0
fi

if [ -d "$TEMP_DIR" ]; then
    rm -rf "$TEMP_DIR"
fi

composer create-project laravel/laravel "$TEMP_DIR"

cp -R "$TEMP_DIR"/. .
rm -rf "$TEMP_DIR"

cp .env.example .env
php artisan key:generate

composer require livewire/livewire
composer require laravel/breeze --dev
php artisan breeze:install livewire

npm install
npm run build

echo "MannaRise Laravel and Livewire scaffold completed."
echo "Next: update your .env database settings, then run: php artisan migrate"
