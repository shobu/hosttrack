<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Notifications\ExpiringHostingNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class SendHostingExpiryNotifications extends Command
{
    protected $signature = 'hosting:send-expiry-notifications';
    protected $description = 'Αποστολή ειδοποιήσεων για φιλοξενίες που λήγουν σε 30 ημέρες.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $clients = Client::whereBetween('hosting_expiration_date', [
            Carbon::now()->addDays(1)->startOfDay(),
            Carbon::now()->addDays(30)->endOfDay()
        ])->get();
        

        if ($clients->count() > 0) {
            foreach ($clients as $client) {
                // Στείλε ειδοποίηση στον διαχειριστή (π.χ. admin@example.com)
                Notification::route('mail', 'vassilis@teamapp.gr')->notify(new ExpiringHostingNotification($client));
                $this->info('Εστάλη ειδοποίηση για τον πελάτη: ' . $client->domain_name);
            }
        } else {
            $this->info('Δεν βρέθηκαν πελάτες που λήγουν σε 30 ημέρες.');
        }
    }
}
