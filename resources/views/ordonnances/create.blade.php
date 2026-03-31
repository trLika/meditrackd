@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4>Nouvelle Ordonnance pour {{ $patient->nom }} {{ $patient->prenom }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('ordonnances.store') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <div class="mb-3">
                    <label class="form-label fw-bold">Médicaments et Instructions</label>
                    <textarea name="contenu" class="form-control" rows="10" placeholder="Ex: Paracétamol 500mg..."></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer et Générer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
