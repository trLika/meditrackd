@extends('layouts.app')

@section('content')
<style>
.glassmorphism-bg {
    background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 35%, #81C784 65%, #E8F5E9 100%);
    position: relative;
    overflow: hidden;
}

.glassmorphism-bg::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,133.3C960,128,1056,96,1152,90.7C1248,85,1344,107,1392,117.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
}

.glass-card {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    border-radius: 20px;
}

.glass-input {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(5px);
    color: white;
}

.glass-input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.glass-input:focus {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    color: white;
    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
}

.glass-label {
    color: rgba(255, 255, 255, 0.9);
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

.floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 0;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
    animation: float 6s ease-in-out infinite;
}

.shape1 {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.shape2 {
    width: 120px;
    height: 120px;
    top: 60%;
    right: 10%;
    animation-delay: 2s;
}

.shape3 {
    width: 60px;
    height: 60px;
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.glass-button {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(5px);
    color: white;
    transition: all 0.3s ease;
}

.glass-button:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 10px 40px rgba(31, 38, 135, 0.5);
}
</style>

<div class="glassmorphism-bg d-flex align-items-center justify-content-center min-vh-100 position-relative">
    <!-- Formes flottantes animées -->
    <div class="floating-shapes">
        <div class="shape shape1"></div>
        <div class="shape shape2"></div>
        <div class="shape shape3"></div>
    </div>

    <!-- Contenu principal -->
    <div class="col-md-5 col-lg-4 position-relative z-10">
        <div class="glass-card p-5">
            <!-- Logo et titre -->
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-20 mb-3" 
                     style="width: 80px; height: 80px;">
                    <i class="bi bi-hospital text-white" style="font-size: 2.5rem;"></i>
                </div>
                <h2 class="text-white fw-bold mb-2">MediTrackD</h2>
                <p class="text-white-50 mb-0">Connexion à votre espace médical</p>
            </div>

            <!-- Formulaire -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Messages d'erreur -->
                @if ($errors->any())
                    <div class="alert alert-danger bg-white bg-opacity-10 border border-white border-opacity-20 text-white mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="form-label glass-label fw-bold">
                        <i class="bi bi-envelope me-2"></i> Adresse Email
                    </label>
                    <input type="email" name="email" class="form-control form-control-lg glass-input rounded-pill" 
                           placeholder="votre@email.com" required>
                </div>

                <div class="mb-4">
                    <label class="form-label glass-label fw-bold">
                        <i class="bi bi-lock me-2"></i> Mot de passe
                    </label>
                    <input type="password" name="password" class="form-control form-control-lg glass-input rounded-pill" 
                           placeholder="Votre mot de passe" required>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label text-white-50" for="remember">
                            Se souvenir de moi
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn glass-button btn-lg w-100 fw-bold rounded-pill py-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Se connecter
                </button>
            </form>

            <!-- Footer -->
            <div class="text-center mt-4">
                <p class="text-white-50 small mb-0">
                    © 2024 MediTrackD - Système de Gestion Médicale
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
