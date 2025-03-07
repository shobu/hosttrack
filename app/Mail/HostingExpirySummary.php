<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HostingExpirySummary extends Mailable
{
    use Queueable, SerializesModels;

    public $clients;

    public function __construct($clients)
    {
        $this->clients = $clients;
    }

    public function build()
    {
        return $this->subject('Λίστα Πελατών με Λήξη Hosting')
                    ->markdown('emails.hosting_expiry_summary')
                    ->with(['clients' => $this->clients]);
    }
}
