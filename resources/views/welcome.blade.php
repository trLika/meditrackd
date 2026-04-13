@extends('layouts.app')

@section('content')
<style>
.glassmorphism-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    color: white;
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

.shape4 {
    width: 100px;
    height: 100px;
    top: 40%;
    right: 30%;
    animation-delay: 1s;
}

.shape5 {
    width: 90px;
    height: 90px;
    bottom: 30%;
    right: 15%;
    animation-delay: 3s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

@keyframes heartBeat {
    0% { transform: scale(1); }
    14% { transform: scale(1.3); }
    28% { transform: scale(1); }
    42% { transform: scale(1.3); }
    70% { transform: scale(1); }
}

.heart-icon {
    font-size: 15rem;
    color: #dc3545;
    animation: heartBeat 1.5s ease-in-out infinite;
    text-shadow: 0 0 30px rgba(220, 53, 69, 0.5);
}

.glass-text {
    color: rgba(255, 255, 255, 0.9);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.glass-title {
    color: white;
    text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
    font-weight: bold;
}
</style>

<div class="glassmorphism-bg d-flex align-items-center justify-content-center min-vh-100 position-relative">
    <!-- Formes flottantes animées -->
    <div class="floating-shapes">
        <div class="shape shape1"></div>
        <div class="shape shape2"></div>
        <div class="shape shape3"></div>
        <div class="shape shape4"></div>
        <div class="shape shape5"></div>
    </div>

    <!-- Contenu principal -->
    <div class="container position-relative z-10">
        <div class="row align-items-center" style="min-height: 80vh;">
            <div class="col-md-6">
                <div class="glass-card p-5">
                    <h1 class="display-3 glass-title mb-4">
                        <i class="bi bi-hospital me-3"></i>MediTrackD
                    </h1>
                    <p class="lead glass-text mb-4">
                        Gérez vos dossiers médicaux de manière efficace et sécurisée avec MediTrackD, votre système de gestion intégré de dossiers médicaux.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn glass-button btn-lg px-5 py-3 rounded-pill fw-bold">
                            <i class="bi bi-arrow-right-circle me-2"></i>
                            Accéder à MediTrackD
                        </a>
                    </div>
                    
                    <!-- Features -->
                    <div class="row mt-5">
                        <div class="col-4 text-center">
                            <div class="glass-feature">
                                <i class="bi bi-shield-check text-white fs-2 mb-2"></i>
                                <h6 class="text-white small">Sécurisé</h6>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="glass-feature">
                                <i class="bi bi-lightning-charge text-white fs-2 mb-2"></i>
                                <h6 class="text-white small">Rapide</h6>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="glass-feature">
                                <i class="bi bi-graph-up text-white fs-2 mb-2"></i>
                                <h6 class="text-white small">Efficace</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <div class="heart-icon">
                    <i class="bi bi-heart-pulse"></i>
                </div>
                <p class="text-white-50 mt-3">
                    Votre partenaire santé de confiance
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
