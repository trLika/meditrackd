@extends('layouts.app')

@section('content')
<style>
    .glassmorphism-bg {
        background: linear-gradient(-50deg, #640629, #74d1f8, #b2dafa, #a34862);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
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
        width: 100%;
        max-width: 500px;
    }
    .glass-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }
    .glass-input:focus { 
        background: rgba(255, 255, 255, 0.2); 
        color: white; 
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        border-color: rgba(220, 53, 69, 0.5);
    }
    .glass-label { color: rgba(255, 255, 255, 0.9); }
    .glass-select {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }
    .glass-select option {
        background: #a34862;
        color: white;
    }
</style>

<div class="glassmorphism-bg">
    <div class="glass-card">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color: #dc3545; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);">MediTrackD</h2>
            <p class="text-white-50">Créer un nouveau compte</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label glass-label">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control glass-input @error('name') is-invalid @enderror" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label glass-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control glass-input @error('email') is-invalid @enderror" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label glass-label">Rôle</label>
                <select name="role" class="form-select glass-select @error('role') is-invalid @enderror" required>
                    <option value="medecin" {{ old('role') == 'medecin' ? 'selected' : '' }}>Médecin</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label glass-label">Service</label>
                <select name="service" class="form-select glass-select @error('service') is-invalid @enderror" required>
                    <option value="" disabled selected>Choisir un service</option>
                    @foreach($services as $service)
                        <option value="{{ $service->name }}" {{ old('service') == $service->name ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
                @error('service')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label glass-label">Mot de passe</label>
                <input type="password" name="password"
                       class="form-control glass-input @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label glass-label">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation"
                       class="form-control glass-input" required>
            </div>

            <button type="submit" class="btn w-100 py-2 fw-bold mb-3" 
                    style="background: linear-gradient(45deg, #dc3545, #a02833); color: white; border: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3); border-radius: 8px;">
                S'inscrire
            </button>

            <div class="text-center">
                <p class="text-white-50 mb-0">Déjà un compte ? 
                    <a href="{{ route('login') }}" class="text-white fw-bold text-decoration-none">Se connecter</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
