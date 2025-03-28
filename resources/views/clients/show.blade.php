@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Πληροφορίες Πελάτη</h2>

    <div class="card">
        <div class="card-body">
            <h4>{{ $client->first_name }} {{ $client->last_name }}</h4>
            <p><strong>Domain:</strong> {{ $client->domain_name }}</p>
            <p><strong>ΑΦΜ:</strong> {{ $client->afm }}</p>
            <p><strong>Email:</strong> {{ $client->email }}</p> 
            <p><strong>Εταιρεία:</strong> {{ $client->company ?? '-' }}</p>
            <p><strong>Κόστος Φιλοξενίας:</strong> €{{ $client->hosting_cost }}</p>
            <p><strong>Server Φιλοξενίας:</strong>
                @if ($client->server)
                    {{ $client->server->name }} ({{ $client->server->ip_address }}) - {{ $client->hosting_company }}
                @else
                    <em>Δεν έχει οριστεί server</em>
                @endif
            </p>
            <p><strong>Ημερομηνία Έναρξης:</strong> {{ \Carbon\Carbon::parse($client->hosting_start_date)->format('d/m/Y') }}</p>
            <p><strong>Ημερομηνία Λήξης:</strong> {{ \Carbon\Carbon::parse($client->hosting_expiration_date)->format('d/m/Y') }}</p>
            <p><strong>Σημειώσεις:</strong> {{ $client->notes ?? '-' }}</p>

            <hr>

            <div class="d-flex gap-2">
                <!-- Κουμπί Επεξεργασίας -->
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">
                    Επεξεργασία
                </a>

                <!-- Κουμπί Ανανέωσης -->
                @php
                    $canRenew = \Carbon\Carbon::parse($client->hosting_expiration_date)->lte(\Carbon\Carbon::now()->addDays(30));
                @endphp
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#renewalModal-{{ $client->id }}" {{ $canRenew ? '' : 'disabled' }}>
                    Ανανέωση Hosting
                </button>


                <!-- Κουμπί Διαγραφής -->
                <form id="delete-form-{{ $client->id }}" action="{{ route('clients.destroy', $client) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger"
                            onclick="confirmAction('delete-form-{{ $client->id }}', 'Θέλεις να διαγράψεις αυτόν τον πελάτη;')">
                        Διαγραφή Πελάτη
                    </button>
                </form>

                @if($client->status === 'active')
                    <form action="{{ route('clients.deactivate', $client) }}" method="POST" onsubmit="return confirm('Είσαι σίγουρος ότι θέλεις να απενεργοποιήσεις αυτόν τον πελάτη;');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning">Απενεργοποίηση</button>
                    </form>
                @else
                    <form action="{{ route('clients.activate', $client) }}" method="POST" onsubmit="return confirm('Είσαι σίγουρος ότι θέλεις να ενεργοποιήσεις αυτόν τον πελάτη;');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Ενεργοποίηση</button>
                    </form>
                @endif


                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Επιστροφή στη Λίστα</a>
            </div>

            <!-- Modal για Ανανέωση Hosting -->
            <div class="modal fade" id="renewalModal-{{ $client->id }}" tabindex="-1" aria-labelledby="renewalModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ανανέωση Hosting για {{ $client->domain_name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="renewalForm-{{ $client->id }}" action="{{ route('clients.renew', $client) }}" method="POST">
                                @csrf
                                <label for="renewalMonths">Μήνες:</label>
                                <select name="months" id="renewalMonths-{{ $client->id }}" class="form-control" onchange="toggleCustomMonths({{ $client->id }})">
                                    <option value="3">3 μήνες</option>
                                    <option value="6">6 μήνες</option>
                                    <option value="12" selected>12 μήνες</option>
                                    <option value="custom">Άλλο...</option>
                                </select>

                                <!-- Custom μήνες -->
                                <div id="customMonthsContainer-{{ $client->id }}" style="display: none; margin-top: 10px;">
                                    <label for="customMonths">Εισαγωγή Μηνών:</label>
                                    <input type="number" name="custom_months" id="customMonths-{{ $client->id }}" class="form-control" min="1" placeholder="Πληκτρολογήστε μήνες">
                                </div>

                                <label for="renewalAmount" class="mt-3">Ποσό πληρωμής (€):</label>
                                <input type="number" name="amount" id="renewalAmount-{{ $client->id }}" class="form-control" value="{{ $client->hosting_cost }}" required>

                                <label for="invoiceNumber" class="mt-3">Αριθμός Τιμολογίου (προαιρετικό):</label>
                                <input type="text" name="invoice_number" id="invoiceNumber-{{ $client->id }}" class="form-control">

                                <!-- Νέο Checkbox για Υποστήριξη -->
                                <div class="form-check mt-3">
                                    <input type="checkbox" class="form-check-input" id="supportService-{{ $client->id }}" name="support_service" onchange="toggleSupportCost({{ $client->id }})">
                                    <label class="form-check-label" for="supportService-{{ $client->id }}">Προσθήκη Υποστήριξης (Security Updates & Backup) +120€/έτος</label>
                                </div>

                                <!-- Πεδίο κόστους υποστήριξης (εμφανίζεται μόνο αν είναι τσεκαρισμένο το checkbox) -->
                                <div id="supportCostContainer-{{ $client->id }}" style="display: none; margin-top: 10px;">
                                    <label for="supportCost">Προσαρμοσμένο Κόστος Υποστήριξης:</label>
                                    <input type="number" step="0.01" name="support_cost" id="supportCost-{{ $client->id }}" class="form-control" value="120">
                                </div>

                                <button type="submit" class="btn btn-success mt-3">Ανανέωση</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            
        </div>
    </div>
    <div style="margin-top: 20px;">
        <h3>Ιστορικό Ανανέωσης</h3>
        <h5>Σύνολο πληρωμών: {{ number_format($client->payments->sum('amount') + $client->payments->sum('support_cost'), 2) }} €</h5>
        <div class="card">
            <div class="card-body">
                @if ($client->renewalLogs->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Προηγούμενη Λήξη</th>
                                <th>Νέα Λήξη</th>
                                <th>Ημερομηνία Ανανέωσης</th>
                                <th>Ποσό Φιλοξενίας (€)</th>
                                <th>Ποσό Υποστήριξης (€)</th>
                                <th>Τιμολόγιο</th>
                                <th>Ενέργειες</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($client->renewalLogs as $log)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($log->old_expiration_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($log->new_expiration_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($log->renewed_at)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $log->payment ? number_format($log->payment->amount, 2) : '-' }} €</td>
                                    <td>
                                        {{ $log->payment && $log->payment->support_service ? number_format($log->payment->support_cost, 2) : '-' }} €
                                    </td>
                                    <td>{{ $log->payment ? $log->payment->invoice_number : '-' }}</td>
                                    <td>
                                        <!-- Φόρμα Διαγραφής Ανανεώσεων -->
                                        <form action="{{ route('renewals.delete', ['renewal' => $log->id]) }}" method="POST" onsubmit="return confirm('Είσαι σίγουρος ότι θέλεις να διαγράψεις αυτή την ανανέωση και την αντίστοιχη πληρωμή;');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Διαγραφή</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Δεν υπάρχουν ανανεώσεις για αυτόν τον πελάτη.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        if (confirm('Είσαι σίγουρος ότι θέλεις να διαγράψεις αυτόν τον πελάτη;')) {
            event.target.closest('form').submit();
        }
    }
</script>

@endsection
