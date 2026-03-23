@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h4 class="mb-0 fw-bold text-success">Ajouter un nouveau patient</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('patients.store') }}" method="POST">
                @csrf <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom</label>

                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" placeholder="Ex: DIALLO" required>

@error('nom')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="prenom" class="form-control" placeholder="Ex: Aissatou" required>
                    </div>

<div class="mb-3">
    <label class="form-label fw-bold">Sexe</label>
    <select name="sexe" class="form-select">
        <option value="">Choisir...</option>
        <option value="M">Masculin</option>
        <option value="F">Féminin</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-bold">Adresse</label>
    <input type="text" name="adresse" class="form-control" placeholder="Ex: Bamako, Rue 12">
</div>

                <div class="row mb-3">
                    <div class="col-md-6">
                       <div class="col-md-6">
    <label class="form-label">Téléphone</label>
    <input type="text" name="telephone"
           class="form-control @error('telephone') is-invalid @enderror"
           value="{{ old('telephone') }}"
           placeholder="Ex: 70000000" required>

    @error('telephone')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
                    <div class="col-md-6">
                        <label class="form-label">Groupe Sanguin</label>
                        <select name="groupe_sanguin" class="form-select" required>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>

                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success ">Enregistrer le patient</button>
                    <a href="{{ route('patients.index') }}" class="btn btn-danger">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
