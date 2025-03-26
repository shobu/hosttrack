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
                @php
                    $borderClass = '';
                    if ($client->hosting_expiration_date < now()) {
                        $borderClass = 'border-left-danger'; // Αν έχει λήξει, να είναι κόκκινο
                    } elseif ($client->can_renew) {
                        $borderClass = 'border-left-warning'; // Αν είναι 1 μήνα πριν τη λήξη, να είναι κίτρινο
                    }
                @endphp
                <tr class="{{ $borderClass }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                    <td>{{ $client->domain_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($client->hosting_expiration_date)->format('d/m/Y') }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <!-- Κουμπί Προβολής Πελάτη -->
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-info btn-sm">Προβολή</a>

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
        <div class="w-100 text-center">
            {{ $clients->links() }}
        </div>
    </div>
</div>
@endsection