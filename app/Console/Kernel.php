<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // Αν έχεις custom artisan commands, τα δηλώνεις εδώ
    protected $commands = [
        \App\Console\Commands\SendHostingExpiryNotifications::class,
    ];
}