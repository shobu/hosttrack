<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\RenewalLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Συνολικός αριθμός πελατών
        $totalClients = Client::count();

        // Πελάτες που η φιλοξενία τους λήγει μέσα στις επόμενες 30 ημέρες
        $expiringClients = Client::whereBetween('hosting_expiration_date', [
            Carbon::now()->startOfDay(),
            Carbon::now()->addDays(30)->endOfDay()
        ])->count();

        // Αριθμός ανανεώσεων που έγιναν τον τελευταίο μήνα
        $recentRenewals = RenewalLog::whereBetween('renewed_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->count();

        // Στατιστικά ανανεώσεων ανά μήνα για τους τελευταίους 6 μήνες
        $renewalsPerMonth = RenewalLog::selectRaw('YEAR(renewed_at) as year, MONTH(renewed_at) as month, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        return view('dashboard.index', compact('totalClients', 'expiringClients', 'recentRenewals', 'renewalsPerMonth'));
    }
}
