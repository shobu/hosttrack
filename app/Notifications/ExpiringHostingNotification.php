<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpiringHostingNotification extends Notification
{
    use Queueable;

    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Ειδοποίηση: Λήξη Φιλοξενίας Πελάτη')
            ->greeting('Γεια σου,')
            ->line('Η φιλοξενία του πελάτη ' . $this->client->first_name . ' ' . $this->client->last_name . ' λήγει σε 30 ημέρες.')
            ->line('Domain: ' . $this->client->domain_name)
            ->line('Ημερομηνία λήξης: ' . \Carbon\Carbon::parse($this->client->hosting_expiration_date)->format('d/m/Y'))
            ->action('Διαχείριση Πελάτη', url('/clients/' . $this->client->id . '/show'))
            ->line('Ελέγξτε αν χρειάζεται να ανανεώσετε τη φιλοξενία.');
    }
}
