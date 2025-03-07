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
                @php
                    $canRenew = \Carbon\Carbon::parse($client->hosting_expiration_date)->lte(\Carbon\Carbon::now()->addDays(30));
                @endphp

                <button class="btn btn-success renew-btn" data-client-id="{{ $client->id }}" {{ $canRenew ? '' : 'disabled' }}>
                    Ανανέωση Hosting
                </button>

                <!-- Form για ανανέωση (θα υποβάλλεται από JavaScript) -->
                <form id="renewalForm-{{ $client->id }}" action="{{ route('clients.renew', $client) }}" method="POST" style="display:none;">
                    @csrf
                    <input type="hidden" name="months" id="renewalMonths-{{ $client->id }}" value="12"> 
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
