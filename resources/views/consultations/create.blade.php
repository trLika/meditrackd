@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-gradient-info text-dark p-3 rounded-top-4">
            <h4 class="mb-0">
                <i class="bi bi-clipboard2-pulse me-2 text-info"></i>
                Fiche de Consultation : <strong>{{ $patient->nom }} {{ $patient->prenom }}</strong>
            </h4>
        </div>

        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('consultations.store', $patient->id) }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Date de consultation</label>
                        <input type="date" name="date_consultation" class="form-control"
                               value="{{ old('date_consultation', date('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Poids (kg)</label>
                        <input type="number" step="0.1" name="poids" class="form-control"
                               value="{{ old('poids') }}" placeholder="Ex: 75.5">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tension Artérielle</label>
                        <input type="text" name="tension" class="form-control"
                               value="{{ old('tension') }}" placeholder="Ex: 12/8">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Symptômes / Motifs</label>
                        <textarea name="symptomes" class="form-control" rows="3">{{ old('symptomes') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold text-primary">Diagnostic</label>
                        <textarea name="diagnostic" class="form-control" rows="3" required>{{ old('diagnostic') }}</textarea>
                        <div class="form-text">Minimum 10 caractères.</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold text-success">Traitement / Ordonnance</label>
                        <textarea name="traitement" class="form-control" rows="4" required>{{ old('traitement') }}</textarea>
                        <div class="form-text">Minimum 10 caractères.</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-5">
                   <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-primary px-5 fw-bold">
                        <i class="bi bi-check-circle me-2"></i> Enregistrer la Consultation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
