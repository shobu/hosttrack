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
            'afm' => 'required|unique:clients',
            'email' => 'required|email',
            'hosting_cost' => 'required|numeric',
            'hosting_start_date' => 'required|date',
            'hosting_expiration_date' => 'required|date|after:hosting_start_date'
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
            'afm' => 'required|unique:clients,afm,' . $client->id,
            'email' => 'required|email',
            'hosting_cost' => 'required|numeric',
            'hosting_start_date' => 'required|date',
            'hosting_expiration_date' => 'required|date|after:hosting_start_date'
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
        $newExpirationDate = Carbon::parse($client->hosting_expiration_date)->addYear();
        $client->update(['hosting_expiration_date' => $newExpirationDate]);

        return redirect()->route('clients.index')->with('success', 'Η φιλοξενία ανανεώθηκε.');
    }
}
