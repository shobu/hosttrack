@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Επεξεργασία Πελάτη</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clients.update', $client) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Όνομα</label>
            <input type="text" name="first_name" class="form-control" value="{{ $client->first_name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Επώνυμο</label>
            <input type="text" name="last_name" class="form-control" value="{{ $client->last_name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Domain Name</label>
            <input type="text" name="domain_name" class="form-control" value="{{ $client->domain_name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">ΑΦΜ</label>
            <input type="text" name="afm" class="form-control" value="{{ $client->afm }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $client->email }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Κόστος Φιλοξενίας</label>
            <input type="number" step="0.01" name="hosting_cost" class="form-control" value="{{ $client->hosting_cost }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ημερομηνία Έναρξης</label>
            <input type="date" name="hosting_start_date" class="form-control" value="{{ $client->hosting_start_date->format('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ημερομηνία Λήξης</label>
            <input type="date" name="hosting_expiration_date" class="form-control" value="{{ $client->hosting_expiration_date->format('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Σημειώσεις</label>
            <textarea name="notes" class="form-control">{{ $client->notes }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Αποθήκευση</button>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Ακύρωση</a>
    </form>
</div>
@endsection
