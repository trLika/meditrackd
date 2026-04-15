@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center rounded-top-4">
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-person-plus me-2"></i> Nouveau Patient
            </h4>
            <small class="opacity-75">MédiTrackD</small>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('patients.store') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nom</label>
                        <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" placeholder="Ex: DIALLO" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Prénom</label>
                        <input type="text" name="prenom" class="form-control" value="{{ old('prenom') }}" placeholder="Ex: Mohamed" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Date de Naissance</label>
                        <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Sexe</label>
                        <select name="sexe" class="form-select" required>
                            <option value="">Choisir...</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Groupe Sanguin</label>
                        <select name="groupe_sanguin" class="form-select">
                            <option value="">Non spécifié</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gs)
                                <option value="{{ $gs }}">{{ $gs }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}" placeholder="Ex: 70000000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Adresse</label>
                        <input type="text" name="adresse" class="form-control" value="{{ old('adresse') }}" placeholder="Ex: Bamako, Rue 123">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Antécédents Médicaux</label>
                        <textarea name="antecedents" class="form-control" rows="3" placeholder="Ex: Asthme, Diabète...">{{ old('antecedents') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Allergies connues</label>
                        <textarea name="allergies" class="form-control" rows="3" placeholder="Ex: Pénicilline, Arachides...">{{ old('allergies') }}</textarea>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_critique" value="1" class="form-check-input" id="critique">
                    <label class="form-check-label fw-bold text-danger" for="critique">Marquer comme Cas Critique</label>
                </div>


<div class="mb-3">
    <label class="form-label fw-bold">Service médical</label>
    <select name="service_id" class="form-select" required>
        <option value="">Sélectionnez un service...</option>

        @if(isset($services) && $services->count() > 0)
            @foreach($services as $service)
                <option value="{{ $service->id }}">
                    {{ $service->name }}
                </option>
            @endforeach
        @else
            <option value="" disabled>Aucun service disponible</option>
        @endif

    </select>
</div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-success px-4">Enregistrer le patient</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
