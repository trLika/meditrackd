@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-info text-white p-3">
                    <h4 class="mb-0">Fiche de Consultation : {{ $patient->nom }} {{ $patient->prenom }}</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('consultations.store', $patient->id) }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Date de consultation</label>
                                <input type="date" name="date_consultation" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Poids (kg)</label>
                                <input type="number" step="0.1" name="poids" class="form-control" placeholder="70.5">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Tension</label>
                                <input type="text" name="tension" class="form-control" placeholder="12/8">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Symptômes</label>
                            <textarea name="symptomes" class="form-control" rows="2" placeholder="Ex: Fièvre, toux..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Diagnostic</label>
                            <textarea name="diagnostic" class="form-control" rows="3" required placeholder="Observations médicales..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Traitement / Ordonnance</label>
                            <textarea name="traitement" class="form-control" rows="3" required placeholder="Médicaments prescrits..."></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-info btn-lg">Enregistrer la consultation</button>
                            <a href="{{ route('patients.index') }}" class="btn btn-danger text-muted">Annuler la consultation</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
