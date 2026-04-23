@extends('layouts.app')

@section('content')
<style>
    .glassmorphism-bg {
        background: linear-gradient(-50deg, #dc3545, #74d1f8, #b2dafa, #a34862);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .glass-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        transition: all 0.3s ease;
    }
    
    .glass-input:focus { 
        background: rgba(255, 255, 255, 0.2); 
        color: white;
        border-color: rgba(255, 255, 255, 0.4);
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
    }
    
    .glass-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    
    /* Styles spécifiques pour la liste déroulante */
    .glass-input option {
        background: #2c3e50;
        color: white;
        padding: 10px;
    }
    
    .glass-input option:hover,
    .glass-input option:checked {
        background: #34495e;
        color: #28a745;
    }
    
    .glass-label { 
        color: rgba(255, 255, 255, 0.9); 
        font-weight: 500;
    }
    
    .glass-button { 
        background: rgba(40, 167, 69, 0.8); 
        color: white; 
        transition: 0.3s; 
        border: none;
        font-weight: 600;
    }
    
    .glass-button:hover { 
        background: rgba(40, 167, 69, 0.9); 
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
    
    .back-link {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .back-link:hover {
        color: white;
    }
    
    /* Amélioration pour la liste déroulante */
    select.glass-input {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 20px;
        padding-right: 40px;
    }
    
    select.glass-input::-ms-expand {
        display: none;
    }
    
    /* Pour s'assurer que la liste s'ouvre vers le bas */
    .form-select {
        position: relative !important;
        z-index: 1;
    }
</style>

<div class="glassmorphism-bg">
    <div class="col-md-6 col-lg-5">
        <div class="glass-card">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: #28a745; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);">
                    <i class="bi bi-hospital me-2"></i>Inscription MediTrackD
                </h2>
                <p class="text-white-50">Rejoignez MediTrackD et accédez à votre espace médical</p>
            </div>

            <form action="{{ route('register.doctor') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-4" style="background: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.3); color: white;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="form-label glass-label">
                        <i class="bi bi-person-badge me-1"></i>Type de compte
                    </label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role" id="role_medecin" value="medecin" checked>
                                <label class="form-check-label text-white" for="role_medecin">
                                    <i class="bi bi-hospital me-2"></i>Médecin
                                    <small class="d-block text-white-50">Accès complet aux patients de votre service</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role" id="role_stagiaire" value="stagiaire">
                                <label class="form-check-label text-white" for="role_stagiaire">
                                    <i class="bi bi-mortarboard me-2"></i>Stagiaire
                                    <small class="d-block text-white-50">Accès observation aux patients</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label glass-label">Nom</label>
                        <input type="text" name="nom" value="{{ old('nom') }}"
                               class="form-control glass-input" placeholder="Votre nom" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label glass-label">Prénom</label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}"
                               class="form-control glass-input" placeholder="Votre prénom" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label glass-label">Email professionnel</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control glass-input" placeholder="medecin@exemple.com" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label glass-label">Mot de passe</label>
                        <input type="password" name="password"
                               class="form-control glass-input" placeholder="Min. 8 caractères" required minlength="8">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label glass-label">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation"
                               class="form-control glass-input" placeholder="Répéter le mot de passe" required minlength="8">
                    </div>
                </div>

                <div class="mb-4" id="service_section">
                    <label class="form-label glass-label">
                        <i class="bi bi-hospital me-1"></i>Service médical
                    </label>
                    
                    <select name="service_id" id="service_select" class="form-select glass-input" required>
                        <option value="">Sélectionner votre service...</option>
                        @if(isset($services) && $services->count() > 0)
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Aucun service disponible</option>
                        @endif
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn glass-button py-3">
                        <i class="bi bi-person-plus me-2"></i>Créer mon compte
                    </button>
                </div>

                <div class="text-center mt-4">
                    <small class="text-white-50">
                        Vous avez déjà un compte ? 
                        <a href="{{ route('login') }}" class="back-link fw-bold">
                            <i class="bi bi-arrow-left me-1"></i>Retour à la connexion
                        </a>
                    </small>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleMedecin = document.getElementById('role_medecin');
    const roleStagiaire = document.getElementById('role_stagiaire');
    const serviceSection = document.getElementById('service_section');
    const serviceSelect = document.getElementById('service_select');

    function toggleServiceSection() {
        if (roleMedecin.checked) {
            serviceSection.style.display = 'block';
            serviceSelect.required = true;
        } else {
            serviceSection.style.display = 'none';
            serviceSelect.required = false;
            serviceSelect.value = '';
        }
    }

    roleMedecin.addEventListener('change', toggleServiceSection);
    roleStagiaire.addEventListener('change', toggleServiceSection);
    
    // Initialisation
    toggleServiceSection();
});
</script>
@endsection
