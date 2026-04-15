@extends('layouts.app')

@section('content')
<style>
    /* Ton style glassmorphism reste inchangé */
    .glassmorphism-bg {
        background: linear-gradient(135deg, #5b2e7d 0%, #8e4caf 35%, #b081c7 65%, #e5d8e8 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
    }
    .glass-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }
    .glass-input:focus { background: rgba(255, 255, 255, 0.2); color: white; }
    .glass-label { color: rgba(255, 255, 255, 0.9); }
    .glass-button { background: rgba(255, 255, 255, 0.2); color: white; transition: 0.3s; }
    .glass-button:hover { background: rgba(255, 255, 255, 0.3); }
</style>

<div class="glassmorphism-bg">
    <div class="col-md-5 col-lg-4">
        <div class="glass-card">
            <div class="text-center mb-4">
                <h2 class="text-white fw-bold">MediTrackD</h2>
                <p class="text-white-50">Connexion à votre espace médical</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <small>Identifiants incorrects.</small>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label glass-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control glass-input" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label glass-label">Mot de passe</label>
                    <input type="password" name="password"
                           class="form-control glass-input" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label text-white-50" for="remember">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn glass-button w-100 py-2">
                    Se connecter
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
