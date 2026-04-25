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
                    <i class="bi bi-shield-lock-fill me-2"></i>Nouveau mot de passe
                </h2>
                <p class="text-white-50">Définissez votre nouveau mot de passe</p>
            </div>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label class="form-label glass-label">
                        <i class="bi bi-envelope me-1"></i>Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control glass-input @error('email') is-invalid @enderror" placeholder="votre@email.com" required autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-white">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label glass-label">
                        <i class="bi bi-lock me-1"></i>Nouveau mot de passe
                    </label>
                    <input type="password" name="password"
                           class="form-control glass-input @error('password') is-invalid @enderror" placeholder="••••••••" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-white">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label glass-label">
                        <i class="bi bi-lock-check me-1"></i>Confirmer le mot de passe
                    </label>
                    <input type="password" name="password_confirmation"
                           class="form-control glass-input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn w-100 py-3 fw-bold mb-3" style="background: linear-gradient(45deg, #dc3545, #a02833); color: white; border: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3); border-radius: 8px;">
                    <i class="bi bi-check-circle me-2"></i>Réinitialiser
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
