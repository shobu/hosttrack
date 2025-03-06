@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Λίστα Πελατών</h2>
    <a href="{{ route('clients.create') }}" class="btn btn-primary mb-3">Προσθήκη Νέου Πελάτη</a>

    <form action="{{ route('clients.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Αναζήτηση πελάτη, domain ή τιμολογίου..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Αναζήτηση</button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Όνομα</th>
                <th>Domain</th>
                <th>Λήξη Φιλοξενίας</th>
                <th>Ενέργειες</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
                <tr class="@if(Carbon\Carbon::parse($client->hosting_expiration_date)->lt(now())) border-left-danger 
                                @elseif(Carbon\Carbon::parse($client->hosting_expiration_date)->lt(now()->addMonth())) border-left-warning 
                            @endif">
                    <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                    <td>{{ $client->domain_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($client->hosting_expiration_date)->format('d/m/Y') }}</td>
                    <td>
                        @php
                            $expirationDate = \Carbon\Carbon::parse($client->hosting_expiration_date);
                            $now = \Carbon\Carbon::now();
                            $diffInDays = $now->diffInDays($expirationDate);
                            $canRenew = $diffInDays <= 30;
                        @endphp
                        <a href="{{ route('clients.show', $client) }}" class="btn btn-info">Προβολή</a>
                        <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">Επεξεργασία</a>
                        <form action="{{ route('clients.renew', $client) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success" {{ $canRenew ? '' : 'disabled' }}>
                                Ανανέωση +1 Έτος
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @if($clients->isEmpty())
                <tr>
                    <td colspan="4" class="text-center text-muted">Δεν υπάρχουν πελάτες.</td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $clients->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
