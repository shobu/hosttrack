@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Server: {{ $server->name }}</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>IP:</strong> {{ $server->ip_address }}</p>
            <p><strong>CPU:</strong> {{ $server->cpu ?? '-' }}</p>
            <p><strong>RAM:</strong> {{ $server->memory_gb ?? '-' }} GB</p>
            <p><strong>Disk:</strong> {{ $server->disk_gb ?? '-' }} GB</p>
            <p><strong>Τύπος:</strong> {{ ucfirst($server->type) ?? '-' }}</p>
            <p><strong>Εταιρεία:</strong> {{ $server->hosting_company ?? '-' }}</p>
            <p><strong>Κόστος / Μήνα:</strong> {{ number_format($server->monthly_cost, 2) }} €</p>
            <p><strong>Σημειώσεις:</strong> {{ $server->notes ?? '-' }}</p>
        </div>
    </div>

    <h4>Πελάτες σε αυτόν τον server ({{ $server->clients->count() }})</h4>

    @if ($server->clients->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Όνομα</th>
                    <th>Domain</th>
                    <th>Λήξη Hosting</th>
                    <th>Ενέργειες</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($server->clients as $client)
                    <tr>
                        <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                        <td>{{ $client->domain_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($client->hosting_expiration_date)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-info btn-sm">Προβολή</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Δεν έχουν αντιστοιχιστεί πελάτες σε αυτόν τον server.</p>
    @endif

    <a href="{{ route('servers.index') }}" class="btn btn-secondary mt-3">Επιστροφή στη λίστα</a>
</div>
@endsection