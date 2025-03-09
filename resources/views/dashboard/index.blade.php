@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Dashboard</h2>

        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Συνολικοί Πελάτες</h5>
                        <p class="card-text display-4">{{ $totalClients }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Φιλοξενίες που Λήγουν Σύντομα</h5>
                        <p class="card-text display-4">{{ $expiringClients }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Ανανεώσεις Τελευταίου Μήνα</h5>
                        <p class="card-text display-4">{{ $recentRenewals }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Ληγμένα Hostings</h5>
                        <p class="card-text display-4">{{ $expired }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Συνολικό Εισόδημα από Φιλοξενία -->
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Έσοδα από Φιλοξενία</h5>
                        <p class="card-text">€{{ number_format($totalHostingIncome, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Συνολικό Εισόδημα από Υποστήριξη -->
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Έσοδα από Υποστήριξη</h5>
                        <p class="card-text">€{{ number_format($totalSupportIncome, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Συνολικά Έσοδα -->
            <div class="col-md-4">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Συνολικά Έσοδα</h5>
                        <p class="card-text">€{{ number_format($totalIncome, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<div>
    <h3>Ανανεώσεις Ανά Μήνα</h3>
    <canvas id="renewalsChart"></canvas>
</div>
<div>
<h5>Συνολικές πληρωμές: {{ number_format(\App\Models\PaymentLog::sum('amount'), 2) }} €</h5>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('renewalsChart').getContext('2d');
        var chartData = {
            labels: {!! json_encode($renewalsPerMonth->pluck('month')->map(function ($m) { return date("F", mktime(0, 0, 0, $m, 1)); })) !!},
            datasets: [{
                label: 'Ανανεώσεις',
                data: {!! json_encode($renewalsPerMonth->pluck('total')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
@endsection
