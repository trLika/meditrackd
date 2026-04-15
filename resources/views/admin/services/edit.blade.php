@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Modifier le Service : {{ $service->name }}</h2>
    <form action="{{ route('services.update', $service->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.services._form')
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('services.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
