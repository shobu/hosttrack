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

            <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">Επεξεργασία</a>

            <!-- Κουμπί Διαγραφής με Confirmation -->
            <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Διαγραφή</button>
            </form>

            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Επιστροφή στη Λίστα</a>
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
