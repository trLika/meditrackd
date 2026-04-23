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
    
    .glass-label { 
        color: rgba(255, 255, 255, 0.9); 
        font-weight: 500;
    }
    
    .register-btn {
        background: rgba(40, 167, 69, 0.8); 
        color: white; 
        transition: 0.3s; 
        border: none;
        font-weight: 600;
    }
    
    .register-btn:hover { 
        background: rgba(40, 167, 69, 0.9); 
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
</style>

<div class="glassmorphism-bg">
    <div class="col-md-5 col-lg-4">
        <div class="glass-card">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: #dc3545; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);">
                    <i class="bi bi-hospital me-2"></i>MediTrackD
                </h2>
                <p class="text-white-50">Connexion à votre espace médical</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-4" style="background: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.3); color: white;">
                        <small><i class="bi bi-exclamation-triangle me-2"></i>Identifiants incorrects.</small>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label glass-label">
                        <i class="bi bi-envelope me-1"></i>Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control glass-input" placeholder="votre@email.com" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label glass-label">
                        <i class="bi bi-lock me-1"></i>Mot de passe
                    </label>
                    <input type="password" name="password"
                           class="form-control glass-input" placeholder="••••••••" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label text-white-50" for="remember">
                        <i class="bi bi-check-circle me-1"></i>Se souvenir de moi
                    </label>
                </div>

                <button type="submit" class="btn w-100 py-3 fw-bold mb-3" style="background: linear-gradient(45deg, #dc3545, #a02833); color: white; border: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3); border-radius: 8px;" onmouseover="this.style.background='linear-gradient(45deg, #ff4757, #c0322); this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(220, 53, 69, 0.4)';" onmouseout="this.style.background='linear-gradient(45deg, #dc3545, #a02833); this.style.transform='translateY(0px)'; this.style.boxShadow='0 4px 15px rgba(220, 53, 69, 0.3)';">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </button>

                <div class="text-center">
                    <small class="text-white-50">
                        Nouveau sur MediTrackD ? 
                        <a href="{{ route('register.doctor.form') }}" class="register-btn btn btn-sm px-3 py-1 ms-2">
                            <i class="bi bi-person-plus me-1"></i>S'inscrire
                        </a>
                    </small>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
