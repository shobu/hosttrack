<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\RenewalLog;
use App\Models\PaymentLog;


class ClientController extends Controller
{

    public function index(Request $request)
    {
        $query = Client::where('status', 'active'); // Φιλτράρουμε τους ενεργούς πελάτες

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
            'company' => 'nullable|string|max:255',
            'hosting_cost' => 'required|numeric',
            'hosting_start_date' => 'required|date',
            'hosting_expiration_date' => 'required|date|after:hosting_start_date',
            'notes' => 'nullable|string',
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
            'company' => 'nullable|string|max:255',
            'hosting_cost' => 'required|numeric',
            'hosting_start_date' => 'required|date',
            'hosting_expiration_date' => 'required|date|after:hosting_start_date',
            'notes' => 'nullable|string',
        ]);
    
        $oldDate = \Carbon\Carbon::parse($client->hosting_expiration_date)->format('Y-m-d');
        $newDate = \Carbon\Carbon::parse($validatedData['hosting_expiration_date'])->format('Y-m-d');
        
        if ($oldDate !== $newDate) {
            RenewalLog::create([
                'client_id' => $client->id,
                'old_expiration_date' => $client->hosting_expiration_date,
                'new_expiration_date' => $validatedData['hosting_expiration_date'],
                'renewed_at' => now(),
            ]);
        }
    
        // Ενημέρωση πελάτη
        $client->update($validatedData);
    
        return redirect()->route('clients.show', $client)->with('success', 'Τα στοιχεία του πελάτη ενημερώθηκαν επιτυχώς.');
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

 
    public function renew(Request $request, Client $client)
    {
        // Παίρνουμε τους μήνες είτε από το dropdown είτε από το custom input
        $monthsToAdd = ($request->input('months') === 'custom') 
            ? (int) $request->input('custom_months', 12) 
            : (int) $request->input('months', 12);
    
        $amount = (float) $request->input('amount', $client->hosting_cost); 
        $invoiceNumber = $request->has('invoice_number') ? $request->input('invoice_number') : null;
    
        // Έλεγχος αν επιλέχθηκε υποστήριξη
        $supportService = $request->has('support_service') ? true : false;
        $supportCost = $supportService ? (float) $request->input('support_cost', 120) : null;
    
        if (Carbon::parse($client->hosting_expiration_date)->gt(Carbon::now()->addMonth())) {
            return redirect()->route('clients.index')->with('error', 'Η φιλοξενία μπορεί να ανανεωθεί μόνο όταν απομένει 1 μήνας ή λιγότερο.');
        }
    
        $oldExpirationDate = Carbon::parse($client->hosting_expiration_date);
        $newExpirationDate = $oldExpirationDate->copy()->addMonths($monthsToAdd);
    
        // Ενημέρωση της ημερομηνίας λήξης του πελάτη
        $client->update([
            'hosting_expiration_date' => $newExpirationDate,
        ]);
    
        // **Καταγραφή Πληρωμής στο PaymentLog**
        $paymentLog = new PaymentLog();
        $paymentLog->client_id = $client->id;
        $paymentLog->amount = $amount;
        $paymentLog->payment_date = now();
        $paymentLog->invoice_number = $invoiceNumber;
        $paymentLog->support_service = $supportService;
        $paymentLog->support_cost = $supportCost;
        $paymentLog->save(); // **Τώρα το αντικείμενο έχει `id`**
    
        // **Καταγραφή στο Ιστορικό Ανανέωσης (RenewalLog)**
        RenewalLog::create([
            'client_id' => $client->id,
            'old_expiration_date' => $oldExpirationDate,
            'new_expiration_date' => $newExpirationDate,
            'renewed_at' => now(),
            'payment_id' => $paymentLog->id, // **Σύνδεση με την πληρωμή**
        ]);
    
        return redirect()->route('clients.index')->with('success', "Η φιλοξενία ανανεώθηκε για $monthsToAdd μήνες και καταγράφηκε πληρωμή.");
    }
    
    
    
    public function deletePayment(PaymentLog $payment)
    {
        // Διαγραφή της αντίστοιχης εγγραφής στο renewal_logs
        RenewalLog::where('payment_id', $payment->id)->delete();
    
        // Διαγραφή της πληρωμής
        $payment->delete();
    
        return back()->with('success', 'Η πληρωμή και η αντίστοιχη εγγραφή ανανέωσης διαγράφηκαν επιτυχώς.');
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
            $client->load(['renewalLogs', 'paymentLogs']); // Φόρτωση και των πληρωμών
            $canRenew = \Carbon\Carbon::parse($client->hosting_expiration_date)->lte(now()->addMonth());
            return view('clients.show', compact('client', 'canRenew'));
        }

    public function inactive()
    {
        $clients = Client::where('status', 'inactive')->orderBy('hosting_expiration_date', 'asc')->paginate(10);
        return view('clients.inactive', compact('clients'));
    }

    public function deactivate(Client $client)
    {
        $client->update(['status' => 'inactive']);
        return redirect()->route('clients.index')->with('success', 'Ο πελάτης απενεργοποιήθηκε.');
    }

    public function activate(Client $client)
    {
        $client->update(['status' => 'active']);

        return redirect()->route('clients.inactive')->with('success', 'Ο πελάτης ενεργοποιήθηκε ξανά.');
    }


}
