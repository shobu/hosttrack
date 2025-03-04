<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $validatedData = $request->validate([
            'domain_name' => 'required|unique:clients,domain_name,' . $client->id,
            'first_name' => 'required',
            'last_name' => 'required',
            'afm' => 'required',
            'email' => 'required|email',
            'hosting_cost' => 'required|numeric',
            'hosting_start_date' => 'required|date',
            'hosting_expiration_date' => 'required|date|after:hosting_start_date',
            'notes' => 'nullable|string',
        ]);

        $client->update($validatedData);

        return redirect()->route('clients.index')->with('success', 'Ο πελάτης ενημερώθηκε.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Ο πελάτης διαγράφηκε.');
    }

 
    public function renew(Client $client)
    {
        // Ελέγχουμε αν η φιλοξενία λήγει μέσα στον επόμενο μήνα
        if (Carbon::parse($client->hosting_expiration_date)->gt(Carbon::now()->addMonth())) {
            return redirect()->route('clients.index')->with('error', 'Η φιλοξενία μπορεί να ανανεωθεί μόνο όταν απομένει 1 μήνας ή λιγότερο.');
        }

        // Προσθέτουμε 1 έτος από την ΗΜΕΡΟΜΗΝΙΑ ΛΗΞΗΣ (και όχι από σήμερα)
        $newExpirationDate = Carbon::parse($client->hosting_expiration_date)->addYear();

        // Ενημερώνουμε τη βάση δεδομένων
        $client->update([
            'hosting_expiration_date' => $newExpirationDate,
        ]);
        return redirect()->route('clients.index')->with('success', 'Η φιλοξενία ανανεώθηκε για 1 ακόμη έτος.');
    }

    public function show(Client $client)
        {
            return view('clients.show', compact('client'));
        }

}
