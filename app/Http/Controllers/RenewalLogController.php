<?php

namespace App\Http\Controllers;

use App\Models\RenewalLog;
use App\Models\PaymentLog;
use Illuminate\Http\Request;

class RenewalLogController extends Controller
{
    public function destroy(RenewalLog $renewal)
    {
        // Διαγραφή της σχετικής πληρωμής, αν υπάρχει
        if ($renewal->payment_id) {
            PaymentLog::where('id', $renewal->payment_id)->delete();
        }

        // Διαγραφή της ανανέωσης
        $renewal->delete();

        return back()->with('success', 'Η ανανέωση και η σχετική πληρωμή διαγράφηκαν επιτυχώς.');
    }
}
