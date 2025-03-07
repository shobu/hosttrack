@component('mail::message')
# Hosting Expiry Notification

Οι παρακάτω πελάτες έχουν hosting που λήγει σύντομα:

@foreach ($clients as $client)
- **{{ $client->first_name }} {{ $client->last_name }}**  
  **Domain:** {{ $client->domain_name }}  
  **Ημερομηνία Λήξης:** {{ \Carbon\Carbon::parse($client->hosting_expiration_date)->format('d/m/Y') }}

@endforeach

Παρακαλώ ελέγξτε τις ανανεώσεις.

@component('mail::button', ['url' => url('/clients')])
Διαχείριση Πελατών
@endcomponent

Ευχαριστούμε,  
**Hosting Manager**
@endcomponent
