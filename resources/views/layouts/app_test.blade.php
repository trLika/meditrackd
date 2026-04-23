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
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            background: #212529;
            color: white;
            z-index: 1000;
            top: 0;
            left: 0;
        }
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            background: #e3f2fd;
            padding: 20px;
        }
        .nav-link {
            color: white !important;
            padding: 12px 20px;
            margin: 2px 0;
            text-decoration: none;
            display: block;
        }
        .nav-link:hover {
            background: #343a40 !important;
        }
        .nav-link.active {
            background: #0d6efd !important;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- TOUJOURS afficher le navbar si connecté -->
        @if(Auth::check())
        <div class="d-flex">
            <!-- Sidebar -->
            <nav class="sidebar">
                <div class="p-3">
                    <h5 class="text-center">MediTrackD</h5>
                    <hr class="border-secondary">
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                                <i class="bi bi-house-door"></i> Accueil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('patients.index') }}" class="nav-link {{ request()->is('patients*') ? 'active' : '' }}">
                                <i class="bi bi-people"></i> Patients
                            </a>
                        </li>
                        
                        @if(Auth::user()->hasRole('administrateur'))
                        <li class="nav-item mt-3">
                            <h6 class="text-white-50 px-3">Administration</h6>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.index') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                                <i class="bi bi-gear"></i> Administration
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.medecins-services.index') }}" class="nav-link {{ request()->is('admin/medecins-services*') ? 'active' : '' }}">
                                <i class="bi bi-people-fill"></i> Médecins par Département
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->is('admin/services*') ? 'active' : '' }}">
                                <i class="bi bi-hospital"></i> Services
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                <i class="bi bi-person"></i> Utilisateurs
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
                
                <!-- User info -->
                <div class="p-3 border-top border-secondary" style="position: absolute; bottom: 0; width: 100%;">
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
            
            <!-- Main content -->
            <div class="main-content">
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
    @yield('scripts')
</body>
</html>
