@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Επεξεργασία Server: {{ $server->name }}</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Παρουσιάστηκαν σφάλματα:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('servers.update', $server) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Όνομα Server</label>
            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $server->name) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label for="ip_address" class="form-label">IP Address</label>
            <input type="text" name="ip_address" id="ip_address" class="form-control" required value="{{ old('ip_address', $server->ip_address) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label for="cpu" class="form-label">CPU</label>
            <input type="text" name="cpu" id="cpu" class="form-control" value="{{ old('cpu', $server->cpu) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label for="memory_gb" class="form-label">RAM (GB)</label>
            <input type="number" name="memory_gb" id="memory_gb" class="form-control" value="{{ old('memory_gb', $server->memory_gb) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label for="disk_gb" class="form-label">Χώρος Δίσκου (GB)</label>
            <input type="number" name="disk_gb" id="disk_gb" class="form-control" value="{{ old('disk_gb', $server->disk_gb) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label for="type" class="form-label">Τύπος Server</label>
            <select name="type" id="type" class="form-control">
                <option value="">-- Επιλέξτε --</option>
                <option value="vps" {{ old('type', $server->type) == 'vps' ? 'selected' : '' }}>VPS</option>
                <option value="dedicated" {{ old('type', $server->type) == 'dedicated' ? 'selected' : '' }}>Dedicated</option>
                <option value="shared" {{ old('type', $server->type) == 'shared' ? 'selected' : '' }}>Shared</option>
                <option value="cloud" {{ old('type', $server->type) == 'cloud' ? 'selected' : '' }}>Cloud</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label for="hosting_company" class="form-label">Εταιρεία Hosting</label>
            <input type="text" name="hosting_company" id="hosting_company" class="form-control" value="{{ old('hosting_company', $server->hosting_company) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label for="monthly_cost" class="form-label">Κόστος / Μήνα (€)</label>
            <input type="number" name="monthly_cost" id="monthly_cost" step="0.01" class="form-control" value="{{ old('monthly_cost', $server->monthly_cost) }}">
        </div>

        <div class="col-md-12 mb-3">
            <label for="notes" class="form-label">Σημειώσεις</label>
            <textarea name="notes" id="notes" class="form-control">{{ old('notes', $server->notes) }}</textarea>
        </div>
        </div>
        <button type="submit" class="btn btn-success">Αποθήκευση</button>
        <a href="{{ route('servers.index') }}" class="btn btn-secondary">Ακύρωση</a>
    </form>
</div>
@endsection