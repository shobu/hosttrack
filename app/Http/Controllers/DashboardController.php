<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\RenewalLog;
use App\Models\PaymentLog;
use Carbon\Carbon;
use App\Models\MonthlyServerCost;

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

        // Συνολικός αριθμός πελατών που έχουν λήξει
        $expired = Client::where('hosting_expiration_date', '<', now())->count();

        // ✅ Συνολικά έσοδα από φιλοξενία (Hosting)
        $totalHostingIncome = PaymentLog::sum('amount');

        // ✅ Συνολικά έσοδα από υποστήριξη (Support)
        $totalSupportIncome = PaymentLog::where('support_service', true)->sum('support_cost');

        // ✅ Συνολικά έσοδα από όλα (Hosting + Υποστήριξη)
        $totalIncome = $totalHostingIncome + $totalSupportIncome;

        $totalServerExpenses = MonthlyServerCost::sum('total_cost');

        return view('dashboard.index', compact(
            'totalClients', 'expiringClients', 'recentRenewals', 'renewalsPerMonth', 
            'expired', 'totalHostingIncome', 'totalSupportIncome', 'totalIncome', 'totalServerExpenses'
        ));
    }
}
