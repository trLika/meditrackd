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
</style>

<div class="glassmorphism-bg">
    <div class="col-md-5 col-lg-4">
        <div class="glass-card">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: #dc3545; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);">
                    <i class="bi bi-key-fill me-2"></i>Mot de passe oublié
                </h2>
                <p class="text-white-50">Entrez votre email pour recevoir un lien de réinitialisation</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success mb-4" style="background: rgba(40, 167, 69, 0.2); border: 1px solid rgba(40, 167, 69, 0.3); color: white;">
                    <small><i class="bi bi-check-circle me-2"></i>{{ session('status') }}</small>
                    @if(config('app.debug'))
                        <div class="mt-2 p-2 rounded" style="background: rgba(0,0,0,0.2); border: 1px dashed rgba(255,255,255,0.3);">
                            <p class="mb-1 small text-warning"><i class="bi bi-bug me-1"></i>[Debug Mode] Le lien est disponible dans le fichier : <code>storage/logs/laravel.log</code></p>
                        </div>
                    @endif
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label glass-label">
                        <i class="bi bi-envelope me-1"></i>Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control glass-input @error('email') is-invalid @enderror" placeholder="votre@email.com" required autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn w-100 py-3 fw-bold mb-3" style="background: linear-gradient(45deg, #dc3545, #a02833); color: white; border: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3); border-radius: 8px;">
                    <i class="bi bi-send me-2"></i>Envoyer le lien
                </button>

                <div class="text-center mb-3">
                    <span class="text-white-50">OU</span>
                </div>

                <a href="{{ route('password.security.step1') }}" class="btn btn-outline-light w-100 py-3 fw-bold mb-4" style="border-radius: 8px; border: 1px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.05);">
                    <i class="bi bi-shield-lock me-2"></i>Réinitialiser via question de sécurité
                </a>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-white-50 text-decoration-none small">
                        <i class="bi bi-arrow-left me-1"></i>Retour à la connexion
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
