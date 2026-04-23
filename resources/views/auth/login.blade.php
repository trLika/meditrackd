@extends('layouts.app')

@section('content')
<style>
    /* Ton style glassmorphism reste inchangé */
    .glassmorphism-bg {
<<<<<<< HEAD
        background: linear-gradient(-50deg, #dc3545, #74d1f8, #b2dafa, #a34862);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
=======
        background: linear-gradient(-50deg, #640629, #74d1f8, #b2dafa, #a34862);
>>>>>>> bff537f7d24ece0821f9fe0015e28a2d91c4ed16
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
                <h2 class="fw-bold" style="color: #dc3545; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);">MediTrackD</h2>
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

                <button type="submit" class="btn w-100 py-2 fw-bold" style="background: linear-gradient(45deg, #dc3545, #a02833); color: white; border: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3); border-radius: 8px;" onmouseover="this.style.background='linear-gradient(45deg, #ff4757, #c0322); this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(220, 53, 69, 0.4)';" onmouseout="this.style.background='linear-gradient(45deg, #dc3545, #a02833); this.style.transform='translateY(0px)'; this.style.boxShadow='0 4px 15px rgba(220, 53, 69, 0.3)';">
                    Se connecter
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
