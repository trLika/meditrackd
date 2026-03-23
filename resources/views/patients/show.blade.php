@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Les informations de votre patient : {{ $patient->nom }} {{ $patient->prenom }}</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>ID :</strong> #{{ $patient->id }}</li>
                <li class="list-group-item"><strong>Téléphone :</strong> {{ $patient->telephone }}</li>
                <li class="list-group-item"><strong>Groupe Sanguin :</strong> {{ $patient->groupe_sanguin }}</li>
                <li class="list-group-item">
    <strong>Sexe :</strong>
    @if($patient->sexe == 'M')
        Masculin
    @elseif($patient->sexe == 'F')
        Féminin
    @else
        <span class="text-muted">Non renseigné</span>
    @endif
</li>
                <li class="list-group-item">
    <i class="bi bi-geo-alt-fill text-danger me-2"></i>
    <strong>Adresse :</strong>
    {{ $patient->adresse ?? 'Non renseignée' }}
</li>
                <li class="list-group-item"><strong>Date d'inscription :</strong> {{ $patient->created_at->format('d/m/Y') }}</li>
            </ul>
            <div class="mt-4">
                <a href="{{ route('patients.index') }}" class="btn btn-danger">Retour à la liste</a>
                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary text-white">Modifier ce dossier</a>
            </div>
        </div>
    </div>
</div>
@endsection
