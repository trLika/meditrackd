<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MediTrackD')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #e3f2fd; }
        .flex-grow-1 { background-color: #e3f2fd; }
    </style>
</head>
<body>
    <div id="app">
        @if(Auth::check())
            <div class="d-flex">
                {{-- Barre latérale --}}
                <nav class="bg-dark text-white shadow" style="width: 260px; position: fixed; height: 100vh; z-index: 1000;">
                    <div class="p-3">
                        <h5 class="text-center">MediTrackD</h5>
                        <hr class="border-secondary">
                        
                        {{-- Menu principal --}}
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <a href="{{ route('home') }}" class="nav-link text-white {{ request()->is('/') ? 'active' : '' }}">
                                    <i class="bi bi-house-door"></i> Accueil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->is('dashboard') ? 'active' : '' }}">
                                    <i class="bi bi-speedometer2"></i> Tableau de bord
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('patients.index') }}" class="nav-link text-white {{ request()->is('patients*') ? 'active' : '' }}">
                                    <i class="bi bi-people"></i> Patients
                                </a>
                            </li>
                            
                            {{-- Menu Administration --}}
                            @if(Auth::user()->hasRole('administrateur'))
                                <li class="nav-item mt-3">
                                    <h6 class="text-white-50 px-3">Administration</h6>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.index') }}" class="nav-link text-white {{ request()->is('admin') ? 'active' : '' }}">
                                        <i class="bi bi-gear"></i> Administration
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.medecins-services.index') }}" class="nav-link text-white {{ request()->is('admin/medecins-services*') ? 'active' : '' }}">
                                        <i class="bi bi-people-fill"></i> Médecins par Département
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.services.index') }}" class="nav-link text-white {{ request()->is('admin/services*') ? 'active' : '' }}">
                                        <i class="bi bi-hospital"></i> Services
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}" class="nav-link text-white {{ request()->is('admin/users*') ? 'active' : '' }}">
                                        <i class="bi bi-person"></i> Utilisateurs
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    
                    {{-- Utilisateur connecté --}}
                    <div class="p-3 border-top border-secondary">
                        <div class="d-flex align-items-center text-white">
                            <div class="flex-grow-1">
                                <strong>{{ Auth::user()->name }}</strong>
                                <br>
                                <small class="text-white-50">{{ Auth::user()->getRoleNames()->first() }}</small>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        </div>
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
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Mon Profil</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Paramètres</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i>Se déconnecter
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </header>

                    @yield('content')
                </div>
            </div>
        @else
            <div class="container">
                @yield('content')
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])
    @yield('scripts')
</body>
</html>
