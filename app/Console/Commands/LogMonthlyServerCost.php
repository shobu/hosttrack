<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Server;
use App\Models\MonthlyServerCost;
use Carbon\Carbon;

class LogMonthlyServerCost extends Command
{
    protected $signature = 'servers:log-monthly-cost';
    protected $description = 'Καταγραφή συνολικού μηνιαίου κόστους servers για τον τρέχοντα μήνα.';

    public function handle()
    {
        $month = Carbon::now()->startOfMonth();

        // Αν υπάρχει ήδη καταγραφή για αυτόν τον μήνα, μην την ξαναδημιουργήσεις
        if (MonthlyServerCost::where('month', $month)->exists()) {
            $this->info('Ήδη υπάρχει καταγραφή για τον μήνα: ' . $month->format('F Y'));
            return;
        }

        $totalCost = Server::sum('monthly_cost');

        MonthlyServerCost::create([
            'month' => $month,
            'total_cost' => $totalCost,
        ]);

        $this->info('Καταγράφηκε το μηνιαίο κόστος: ' . number_format($totalCost, 2) . ' € για ' . $month->format('F Y'));
    }
}