<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SendNotifications;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule notifications command
// Run: php artisan notifications:send
// Schedule: Add to crontab: 0 * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
Artisan::command('notifications:send', function () {
    $this->call(SendNotifications::class);
})->purpose('Send notifications for expiring reservations and overdue loans')->describe('Run all notification tasks');
