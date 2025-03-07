@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ανενεργοί Πελάτες</h2>

    <a href="{{ route('clients.index') }}" class="btn btn-primary mb-3">Προβολή Ενεργών Πελατών</a>

    <table class="table">
        <thead>
            <tr>
                <th>Όνομα</th>
                <th>Επώνυμο</th>
                <th>Domain</th>
                <th>Ημ. Λήξης</th>
                <th>Ενέργειες</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clients as $client)
                <tr>
                    <td>{{ $client->first_name }}</td>
                    <td>{{ $client->last_name }}</td>
                    <td>{{ $client->domain_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($client->hosting_expiration_date)->format('d/m/Y') }}</td>
                    <td>
                        <form action="{{ route('clients.activate', $client) }}" method="POST" onsubmit="return confirm('Είσαι σίγουρος ότι θέλεις να ενεργοποιήσεις αυτόν τον πελάτη;');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">Ενεργοποίηση</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $clients->links() }}
</div>
@endsection
