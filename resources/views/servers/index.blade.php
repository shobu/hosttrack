@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Λίστα Servers</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('servers.create') }}" class="btn btn-primary">Προσθήκη Server</a>

        <div>
            <strong>Σύνολο κόστους / μήνα:</strong>
            {{ number_format($totalMonthlyCost, 2) }} €
        </div>
    </div>
    @if($servers->count())
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Όνομα</th>
                    <th>IP</th>
                    <th>CPU</th>
                    <th>RAM (GB)</th>
                    <th>Disk (GB)</th>
                    <th>Εταιρεία</th>
                    <th>Πελάτες</th>
                    <th>Κόστος / μήνα (€)</th>
                    <th>Ενέργειες</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($servers as $server)
                    <tr>
                        <td>{{ $server->name }}</td>
                        <td>{{ $server->ip_address }}</td>
                        <td>{{ $server->cpu }}</td>
                        <td>{{ $server->memory_gb }}</td>
                        <td>{{ $server->disk_gb }}</td>
                        <td>{{ $server->hosting_company }}</td>
                        <td>{{ $server->clients()->count() }}</td>
                        <td>{{ number_format($server->monthly_cost, 2) }}</td>
                        <td class="d-flex gap-1">
                            <a href="{{ route('servers.show', $server) }}" class="btn btn-info btn-sm">Προβολή</a>
                            <a href="{{ route('servers.edit', $server) }}" class="btn btn-warning btn-sm">Επεξεργασία</a>
                            <form action="{{ route('servers.destroy', $server) }}" method="POST" onsubmit="return confirm('Είσαι σίγουρος;');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Διαγραφή</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Δεν υπάρχουν servers προς το παρόν.</p>
    @endif
</div>
@endsection