@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning">
            <h4 class="mb-0 text-dark">Modifier l'Ordonnance</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('ordonnances.update', $ordonnance->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Détails de la prescription</label>
                    <textarea name="contenu" class="form-control" rows="8" required>{{ $ordonnance->contenu }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Date</label>
                    <input type="date" name="date_prescription" class="form-control" value="{{ $ordonnance->date_prescription }}" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('patients.show', $ordonnance->patient_id) }}" class="btn btn-secondary">Retour</a>
                    <button type="submit" class="btn btn-primary">Mettre à jour l'ordonnance</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
