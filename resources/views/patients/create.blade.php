@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center rounded-top-4">
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-person-plus me-2"></i>
                Nouveau Patient
            </h4>
            <small class="opacity-75">MédiTrackD</small>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('patients.store') }}" method="POST">
                @csrf
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label>Nom</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Ex: DIALLO">
                    </div>
                    
                    <div class="col-md-6">
                        <label>Prénom</label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" placeholder="Ex: Mohamed">
                    </div>
                </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person me-1"></i> Prénom
                            </label>
                            <input type="text" name="prenom" class="form-control form-control-lg @error('prenom') is-invalid @enderror" 
                                   value="{{ old('prenom') }}" placeholder="Ex: Aissatou" required>
                            @error('prenom')

                <div class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label>Date de Naissance</label>
                            <input type="date" name="date_naissance" value="{{ old('date_naissance') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Sexe</label>
                            <select name="sexe">
                                <option value="">Choisir...</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Groupe Sanguin</label>
                            <select name="groupe_sanguin">
                                <option value="">Non spécifié</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Téléphone</label>
                            <input type="text" name="telephone" value="{{ old('telephone') }}" placeholder="Ex: 70000000">
                        </div>
                        <div class="col-md-6">
                            <label>Adresse</label>
                            <input type="text" name="adresse" value="{{ old('adresse') }}" placeholder="Ex: Bamako, Rue 123">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Antécédents Médicaux</label>
                            <textarea name="antecedents" rows="3" placeholder="Ex: Asthme, Diabète, Hypertension..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Allergies connues</label>
                            <textarea name="allergies" rows="3" placeholder="Ex: Pénicilline, Arachides, Pollen..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label>
                        <input type="checkbox" name="is_critique" value="1">
                        Marquer comme Cas Critique
                    </label>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('patients.index') }}" class="btn btn-outline-danger">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-success">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
