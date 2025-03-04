<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\RenewalLog;


class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('hosting_expiration_date', 'asc')->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'domain_name' => 'required|unique:clients',
            'first_name' => 'required',
            'last_name' => 'required',
            'afm' => 'required',
            'email' => 'required|email',
            'hosting_cost' => 'required|numeric',
            'hosting_start_date' => 'required|date',
            'hosting_expiration_date' => 'required|date|after:hosting_start_date',
            'notes' => 'nullable|string',
        ]);

        Client::create($validatedData);

        return redirect()->route('clients.index')->with('success', 'Ο πελάτης προστέθηκε επιτυχώς.');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        // Επικύρωση δεδομένων
        $validatedData = $request->validate([
            'domain_name' => 'required|unique:clients,domain_name,' . $client->id,
            'afm' => 'required',
            'email' => 'required|email',
            'hosting_cost' => 'required|numeric',
            'hosting_start_date' => 'required|date',
            'hosting_expiration_date' => 'required|date|after:hosting_start_date',
            'notes' => 'nullable|string',
        ]);

        // Ελέγχουμε αν η ημερομηνία λήξης έχει αλλάξει
        if ($client->hosting_expiration_date != $validatedData['hosting_expiration_date']) {
            // Καταγραφή της αλλαγής στο ιστορικό ανανεώσεων
            RenewalLog::create([
                'client_id' => $client->id,
                'old_expiration_date' => $client->hosting_expiration_date,
                'new_expiration_date' => $validatedData['hosting_expiration_date'],
                'renewed_at' => now(),
            ]);
        }

        // Ενημέρωση του πελάτη με τα νέα δεδομένα
        $client->update($validatedData);

        return redirect()->route('clients.index')->with('success', 'Τα στοιχεία του πελάτη ενημερώθηκαν επιτυχώς.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Ο πελάτης διαγράφηκε.');
    }

 
    public function renew(Client $client)
    {
        // Έλεγχος αν η φιλοξενία λήγει μέσα στον επόμενο μήνα
        if (Carbon::parse($client->hosting_expiration_date)->gt(Carbon::now()->addMonth())) {
            return redirect()->route('clients.index')->with('error', 'Η φιλοξενία μπορεί να ανανεωθεί μόνο όταν απομένει 1 μήνας ή λιγότερο.');
        }

        // Αποθηκεύουμε ΠΡΩΤΑ την παλιά ημερομηνία πριν την αλλάξουμε
        $oldExpirationDate = Carbon::parse($client->hosting_expiration_date);

        // Δημιουργούμε ΝΕΟ instance με copy() ώστε να μην αλλάξουμε το original
        $newExpirationDate = $oldExpirationDate->copy()->addYear();

        // Ενημέρωση της ημερομηνίας λήξης του πελάτη
        $client->update([
            'hosting_expiration_date' => $newExpirationDate,
        ]);

        // Καταγραφή της ανανέωσης στο ιστορικό
        RenewalLog::create([
            'client_id' => $client->id,
            'old_expiration_date' => $oldExpirationDate,
            'new_expiration_date' => $newExpirationDate,
            'renewed_at' => now(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Η φιλοξενία ανανεώθηκε για 1 ακόμη έτος.');
    }

    public function show(Client $client)
        {
            return view('clients.show', compact('client'));
        }

}
