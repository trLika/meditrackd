@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-warning text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Modifier les données du Patient : {{ $patient->nom }} {{ $patient->prenom }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('patients.update', $patient->id) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nom</label>
                                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $patient->nom) }}" required>
                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Prénom</label>
                                <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom', $patient->prenom) }}" required>
                                @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sexe</label>
                                <select name="sexe" class="form-select">
                                    <option value="M" {{ $patient->sexe == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ $patient->sexe == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>

                                            <div class="col-md-6">
                                            <label class="form-label fw-bold">Âge</label>
                                            <input type="number"
                                              name="age"
                                             class="form-control"
                                            value="{{ $patient->age }}"
                                              min="0" max="120"
                                                   required>
                                      </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Groupe Sanguin</label>
                                <select name="groupe_sanguin" class="form-select">
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $groupe)
                                        <option value="{{ $groupe }}" {{ $patient->groupe_sanguin == $groupe ? 'selected' : '' }}>{{ $groupe }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Téléphone</label>
                                <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone', $patient->telephone) }}" required>
                                @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Adresse</label>
                            <textarea name="adresse" class="form-control" rows="3">{{ old('adresse', $patient->adresse) }}</textarea>
                        </div>
                         <div class="mb-3">
    <label for="antecedents" class="form-label">Antécédents Médicaux</label>
    <textarea name="antecedents" id="antecedents" class="form-control" rows="3">{{ old('antecedents', $patient->antecedents) }}</textarea>
</div>

<div class="mb-3 form-check form-switch">
    <input class="form-check-input" type="checkbox" name="is_critique" id="is_critique" value="1" {{ $patient->is_critique ? 'checked' : '' }}>
    <label class="form-check-label text-danger" for="is_critique">
        <strong>Marquer comme Cas Critique</strong>
    </label>
</div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('patients.index') }}" class="btn btn-danger px-4">Annuler</a>
                            <button type="submit" class="btn btn-warning text-white px-4 fw-bold">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
