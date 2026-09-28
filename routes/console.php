<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
| Shared hosting (cPanel) has no Supervisor, so the scheduler drains the queue every minute.
| Add ONE cron job in cPanel:
|   * * * * * cd /home/USER/advertally && php artisan schedule:run >> /dev/null 2>&1
| On a VPS, run `php artisan queue:work` under Supervisor instead and remove this entry.
*/
Schedule::command('queue:work --stop-when-empty --max-time=55 --tries=3')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('queue:prune-failed --hours=168')->daily();
