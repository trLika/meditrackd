@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Ajouter un Service</h2>
    <form action="{{ route('services.store') }}" method="POST">
        @csrf
        @include('admin.services._form')
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('services.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
