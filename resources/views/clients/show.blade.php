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
            <p><strong>Κόστος Φιλοξενίας:</strong> €{{ $client->hosting_cost }}</p>
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
                <form id="renew-form-{{ $client->id }}" action="{{ route('clients.renew', $client) }}" method="POST">
                    @csrf
                    <button type="button" class="btn btn-success" 
                            onclick="confirmAction('renew-form-{{ $client->id }}', 'Θέλεις να προσθέσεις 1 έτος στη φιλοξενία;')" 
                            {{ $canRenew ? '' : 'disabled' }}>
                        Ανανέωση +1 Έτος
                    </button>
                </form>

                <!-- Κουμπί Διαγραφής -->
                <form id="delete-form-{{ $client->id }}" action="{{ route('clients.destroy', $client) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger"
                            onclick="confirmAction('delete-form-{{ $client->id }}', 'Θέλεις να διαγράψεις αυτόν τον πελάτη;')">
                        Διαγραφή Πελάτη
                    </button>
                </form>

                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Επιστροφή στη Λίστα</a>
            </div>
            
        </div>
    </div>

    <h3>Ιστορικό Ανανέωσης</h3>
   
    @if ($client->renewalLogs->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Προηγούμενη Λήξη</th>
                    <th>Νέα Λήξη</th>
                    <th>Ημερομηνία Ανανέωσης</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($client->renewalLogs as $log)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($log->old_expiration_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->new_expiration_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->renewed_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Δεν υπάρχουν ανανεώσεις για αυτόν τον πελάτη.</p>
    @endif

</div>

<script>
    function confirmDelete() {
        if (confirm('Είσαι σίγουρος ότι θέλεις να διαγράψεις αυτόν τον πελάτη;')) {
            event.target.closest('form').submit();
        }
    }
</script>

@endsection
