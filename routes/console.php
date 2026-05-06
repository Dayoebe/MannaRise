<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('mannarise:send-devotional-reminders')->everyFiveMinutes()->withoutOverlapping();
Schedule::command('mannarise:send-weekly-spiritual-digests')->hourly()->sundays()->withoutOverlapping();
Schedule::command('mannarise:sync-daily-scripture')->dailyAt('03:30')->withoutOverlapping();
Schedule::command('resources:prepare-daily-devotion')->dailyAt('04:00')->withoutOverlapping();
Schedule::command('resources:sync-books')->weekly()->withoutOverlapping();
Schedule::command('resources:sync-audio')->weekly()->withoutOverlapping();
Schedule::command('resources:sync-videos')->weekly()->withoutOverlapping();
