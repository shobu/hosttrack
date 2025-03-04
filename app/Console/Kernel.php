<?php


namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // ✅ Προγραμματισμός της αποστολής ειδοποιήσεων καθημερινά
        $schedule->command('hosting:send-expiry-notifications')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
