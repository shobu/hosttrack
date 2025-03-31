@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Προσθήκη Νέου Πελάτη</h2>
 <hr>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="first_name" class="form-label">Όνομα</label>
                <input type="text" name="first_name" id="first_name" class="form-control" 
                       value="{{ old('first_name', isset($client) ? $client->first_name : '') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="last_name" class="form-label">Επώνυμο</label>
                <input type="text" name="last_name" id="last_name" class="form-control" 
                       value="{{ old('last_name', isset($client) ? $client->last_name : '') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="domain_name" class="form-label">Domain Name</label>
                <input type="text" name="domain_name" id="domain_name" class="form-control" 
                       value="{{ old('domain_name', isset($client) ? $client->domain_name : '') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="afm" class="form-label">ΑΦΜ</label>
                <input type="text" name="afm" id="afm" class="form-control" 
                       value="{{ old('afm', isset($client) ? $client->afm : '') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" 
                       value="{{ old('email', isset($client) ? $client->email : '') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="company" class="form-label">Εταιρεία (αν φιλοξενείται για άλλον)</label>
                <input type="text" name="company" id="company" class="form-control" value="{{ old('company', $client->company ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label for="hosting_cost" class="form-label">Κόστος Φιλοξενίας</label>
                <input type="number" name="hosting_cost" id="hosting_cost" class="form-control" step="0.01"
                       value="{{ old('hosting_cost', isset($client) ? $client->hosting_cost : '') }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="hosting_start_date" class="form-label">Ημερομηνία Έναρξης</label>
                <input type="date" name="hosting_start_date" id="hosting_start_date"
                    class="form-control"
                    value="{{ old('hosting_start_date', isset($client) ? \Carbon\Carbon::parse($client->hosting_start_date)->format('Y-m-d') : '') }}"
                    required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="hosting_expiration_date" class="form-label">Ημερομηνία Λήξης</label>
                <input type="date" name="hosting_expiration_date" id="hosting_expiration_date"
                    class="form-control"
                    value="{{ old('hosting_expiration_date', isset($client) ? \Carbon\Carbon::parse($client->hosting_expiration_date)->format('Y-m-d') : '') }}"
                    required>
            </div>
            <div class="col-md-12 mb-3">
                <label for="server_id" class="form-label">Server Φιλοξενίας</label>
                <select name="server_id" id="server_id" class="form-control">
                    <option value="">-- Καμία Αντιστοίχιση --</option>
                    @foreach($servers as $server)
                        <option value="{{ $server->id }}" {{ old('server_id') == $server->id ? 'selected' : '' }}>
                            {{ $server->name }} ({{ $server->ip_address }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label for="notes" class="form-label">Σημειώσεις</label>
                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', isset($client) ? $client->notes : '') }}</textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Προσθήκη</button>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Ακύρωση</a>
    </form>
</div>
@endsection
