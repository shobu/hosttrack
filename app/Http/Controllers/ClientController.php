<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\RenewalLog;


class ClientController extends Controller
{

    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('domain_name', 'LIKE', "%{$search}%")
                ->orWhere('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhere('afm', 'LIKE', "%{$search}%")
                ->orWhere('notes', 'LIKE', "%{$search}%");
            });
        }

        // Φορτώνουμε τα renewal logs μαζί με τα clients για καλύτερη απόδοση
        $clients = $query->with('renewalLogs')
                        ->orderBy('hosting_expiration_date', 'asc')
                        ->paginate(10);

        return view('clients.index', compact('clients'));
    }


    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {

        // Validation των πεδίων
        $validatedData = $request->validate([
            'domain_name' => 'required|unique:clients',
            'first_name' => 'required',
            'last_name' => 'required',
            'afm' => 'required',
            'email' => 'required|email',
            'hosting_cost' => 'required|numeric',
            'hosting_start_date' => 'required|date',
            'hosting_expiration_date' => 'required|date|after:hosting_start_date',
        ]);
    
        // Μετατροπή ημερομηνιών από `d/m/Y` σε `Y-m-d` πριν αποθηκευτούν στη βάση
        $validatedData['hosting_start_date'] = \Carbon\Carbon::createFromFormat('Y-m-d', $request->hosting_start_date)->format('Y-m-d');
        $validatedData['hosting_expiration_date'] = \Carbon\Carbon::createFromFormat('Y-m-d', $request->hosting_expiration_date)->format('Y-m-d');       
    
        // Δημιουργία νέου πελάτη
        Client::create($validatedData);
    
        return redirect()->route('clients.index')->with('success', 'Ο πελάτης δημιουργήθηκε επιτυχώς.');
    }
    
    
    public function update(Request $request, Client $client)
    {

        // Validation των πεδίων
        $validatedData = $request->validate([
            'domain_name' => 'required|unique:clients,domain_name,' . $client->id,
            'first_name' => 'required',
            'last_name' => 'required',
            'afm' => 'required',
            'email' => 'required|email',
            'hosting_cost' => 'required|numeric',
            'hosting_start_date' => 'required|date',
            'hosting_expiration_date' => 'required|date|after:hosting_start_date',
        ]);
    
        // Μετατροπή ημερομηνιών από `d/m/Y` σε `Y-m-d` πριν αποθηκευτούν στη βάση
        $validatedData['hosting_start_date'] = \Carbon\Carbon::createFromFormat('Y-m-d', $request->hosting_start_date)->format('Y-m-d');
        $validatedData['hosting_expiration_date'] = \Carbon\Carbon::createFromFormat('Y-m-d', $request->hosting_expiration_date)->format('Y-m-d');      
    
        // Αν γίνει αλλαγή στην ημερομηνία λήξης, καταγραφή στο ιστορικό
        if ($client->hosting_expiration_date !== $validatedData['hosting_expiration_date']) {
            RenewalLog::create([
                'client_id' => $client->id,
                'old_expiration_date' => $client->hosting_expiration_date,
                'new_expiration_date' => $validatedData['hosting_expiration_date'],
                'renewed_at' => now(),
            ]);
        }
    
        // Ενημέρωση πελάτη
        $client->update($validatedData);
    
        return redirect()->route('clients.index')->with('success', 'Τα στοιχεία του πελάτη ενημερώθηκαν επιτυχώς.');
    }
    

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Ο πελάτης διαγράφηκε.');
    }

 
    public function renew(Client $client)
    {
        if (!$client->can_renew) {
            return redirect()->route('clients.index')->with('error', 'Η φιλοξενία μπορεί να ανανεωθεί μόνο όταν απομένει 1 μήνας ή λιγότερο.');
        }
    
        // Αποθήκευση προηγούμενης ημερομηνίας
        $oldExpirationDate = clone $client->hosting_expiration_date;
        $newExpirationDate = \Carbon\Carbon::parse($client->hosting_expiration_date)->addYear();
    
        // Ενημέρωση πελάτη
        $client->update(['hosting_expiration_date' => $newExpirationDate]);
    
        // Καταγραφή στο ιστορικό
        RenewalLog::create([
            'client_id' => $client->id,
            'old_expiration_date' => $oldExpirationDate,
            'new_expiration_date' => $newExpirationDate,
            'renewed_at' => now(),
        ]);
    
        return redirect()->route('clients.index')->with('success', 'Η φιλοξενία ανανεώθηκε επιτυχώς.');
    }


    public function updateInvoice(Request $request, RenewalLog $renewalLog)
    {
        $request->validate([
            'invoice_number' => 'nullable|string|max:50'
        ]);

        $renewalLog->update([
            'invoice_number' => $request->invoice_number
        ]);

        return back()->with('success', 'Ο αριθμός τιμολογίου ενημερώθηκε επιτυχώς.');
    }


    public function show(Client $client)
        {
            $canRenew = \Carbon\Carbon::parse($client->hosting_expiration_date)->lte(now()->addMonth());

            return view('clients.show', compact('client', 'canRenew'));
        }

}
