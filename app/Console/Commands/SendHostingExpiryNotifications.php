<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\HostingExpirySummary;
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
        // Εύρεση πελατών που λήγουν σε 30 ημέρες και ταξινόμηση
        $expiryDate = Carbon::now()->addDays(30);
        $expiringClients = Client::where('status', 'active')   // Μόνο ενεργοί πελάτες
                                 ->whereNotNull('hosting_expiration_date') // Αποφυγή κενών ημερομηνιών
                                 ->where('hosting_expiration_date', '<=', $expiryDate)
                                 ->orderBy('hosting_expiration_date', 'asc')
                                 ->get();
    
        // Αν δεν υπάρχουν πελάτες που λήγουν, δεν στέλνουμε email
        if ($expiringClients->isEmpty()) {
            $this->info('Δεν βρέθηκαν πελάτες που λήγουν σύντομα. Δεν στάλθηκε email.');
            return;
        }
    
        // Στέλνουμε **ένα email** με τους πελάτες ταξινομημένους σωστά
        Mail::to('vassilis@teamapp.gr')->send(new HostingExpirySummary($expiringClients));
    
        $this->info('Το email με τις επερχόμενες λήξεις στάλθηκε επιτυχώς.');
    }
}
