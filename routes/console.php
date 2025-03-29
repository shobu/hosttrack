<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 📅 Ειδοποιήσεις για λήξη hosting - κάθε Δευτέρα στις 08:00
app(Schedule::class)
    ->command('hosting:send-expiry-notifications')
    ->weeklyOn(1, '08:00');

// 💰 Καταγραφή κόστους server - κάθε 1η του μήνα στις 02:00
app(Schedule::class)
    ->command('servers:log-monthly-cost')
    ->monthlyOn(1, '02:00');
