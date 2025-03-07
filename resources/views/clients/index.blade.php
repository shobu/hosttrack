@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Λίστα Πελατών</h2>

    <!-- Φόρμα Αναζήτησης -->
    <form method="GET" action="{{ route('clients.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Αναζήτηση πελάτη..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Αναζήτηση</button>
        </div>
    </form>

    <!-- Πίνακας Πελατών -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Όνομα</th>
                    <th>Domain</th>
                    <th>Λήξη Hosting</th>
                    <th>Ενέργειες</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr class="{{ $client->can_renew ? 'border-left-warning' : '' }} {{ $client->hosting_expiration_date < now() ? 'border-left-danger' : '' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                    <td>{{ $client->domain_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($client->hosting_expiration_date)->format('d/m/Y') }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <!-- Κουμπί Προβολής Πελάτη -->
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-info btn-sm">Προβολή</a>

                            <!-- Κουμπί Επεξεργασίας -->
                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning btn-sm">Επεξεργασία</a>

                            <!-- Κουμπί Ανανέωσης (Ενεργοποιείται 1 μήνα πριν τη λήξη) -->
                            @if ($client->can_renew)
                                <form id="renew-form-{{ $client->id }}" action="{{ route('clients.renew', $client) }}" method="POST">
                                    @csrf
                                    <button type="button" class="btn btn-success btn-sm"
                                            onclick="confirmAction('renew-form-{{ $client->id }}', 'Θέλεις να προσθέσεις 1 έτος στη φιλοξενία;')">
                                        Ανανέωση +1 Έτος
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Δεν βρέθηκαν πελάτες.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $clients->links() }}
    </div>
</div>
@endsection