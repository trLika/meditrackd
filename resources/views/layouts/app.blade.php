<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediTrackD - Votre outil de gestion médicale</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            background: linear-gradient(-50deg, #dc3545, #74d1f8, #b2dafa, #a34862);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .flex-grow-1 { 
            background: transparent;
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>

    <div id="app">
        @if(Auth::check() && (request()->is('dashboard*') || request()->is('patients*') || request()->is('admin*')))
            <div class="d-flex">
                {{-- Barre latérale --}}
                <nav class="bg-dark text-white shadow" style="width: 260px; position: fixed; height: 100vh; z-index: 1000;">
                    <div class="p-4">
                        <h4 class="text-danger fw-bold mb-4">MediTrackD</h4>
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item mb-2">
                                <a href="/" class="nav-link text-white"><i class="bi bi-house-door me-2 text-primary"></i> Accueil</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->is('dashboard*') ? 'active' : '' }}">
                                    <i class="bi bi-speedometer2 me-2 text-danger"></i> Tableau de bord
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ route('patients.index') }}" class="nav-link text-white {{ request()->is('patients*') ? 'active' : '' }}">
                                    <i class="bi bi-people me-2 text-success"></i> Patients
                                </a>
                            </li>

                            {{-- Menu Administration sécurisé --}}
                            @if(Auth::user()->role === 'admin')
                            <li class="nav-item mb-2">
                                <a href="{{ route('admin.index') }}" class="nav-link text-white {{ request()->is('admin') ? 'active' : '' }}">
                                    <i class="bi bi-shield-lock me-2 text-warning"></i> Administration
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </nav>

                {{-- Contenu principal --}}
                <div class="flex-grow-1" style="margin-left: 260px; min-height: 100vh; background: transparent; overflow-y: scroll !important;">
                    <header class="navbar navbar-expand-md navbar-light bg-transparent py-3 px-4 mb-3">
                        <div class="container-fluid">
                            <div class="ms-auto dropdown">
                                <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1 text-danger"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </header>

                    <main class="px-4">
                        @yield('content')
                    </main>
                </div>
            </div>
        @else
            {{-- Vue pour les pages hors connexion (Login/Register) --}}
            <div class="min-vh-100">@yield('content')</div>
        @endif
    </div>

    @vite(['resources/js/app.js'])
</body>
</html>
