@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Λίστα Πελατών</h2>
    <a href="{{ route('clients.create') }}" class="btn btn-primary mb-3">Προσθήκη Νέου Πελάτη</a>
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
                <tr>
                    <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                    <td>{{ $client->domain_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($client->hosting_expiration_date)->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">Επεξεργασία</a>
                        <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger">Διαγραφή</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $clients->links() }}
</div>
@endsection
