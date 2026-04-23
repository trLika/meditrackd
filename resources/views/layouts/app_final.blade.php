<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MediTrackD')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { 
            margin: 0; 
            padding: 0; 
            background-color: #e3f2fd; 
            font-family: Arial, sans-serif;
        }
        .navbar-custom {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: #212529;
            color: white;
            z-index: 9999;
            overflow-y: auto;
        }
        .content-area {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background: #e3f2fd;
        }
        .nav-item-custom {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid #343a40;
        }
        .nav-item-custom:hover {
            background: #343a40;
            color: white;
        }
        .nav-item-custom.active {
            background: #0d6efd;
            color: white;
        }
        .user-section {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 15px;
            background: #343a40;
            border-top: 1px solid #495057;
        }
    </style>
</head>
<body>
@if(Auth::check())
<div class="navbar-custom">
    <div style="padding: 20px; text-align: center; border-bottom: 1px solid #495057;">
        <h5 style="color: white; margin: 0;">MediTrackD</h5>
    </div>
    
    <div>
        <a href="{{ route('dashboard') }}" class="nav-item-custom {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> Accueil
        </a>
        <a href="{{ route('dashboard') }}" class="nav-item-custom {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Tableau de bord
        </a>
        <a href="{{ route('patients.index') }}" class="nav-item-custom {{ request()->is('patients*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Patients
        </a>
        
        @if(Auth::user()->hasRole('administrateur'))
        <div style="padding: 10px 20px; color: #6c757d; font-size: 12px; text-transform: uppercase;">
            Administration
        </div>
        <a href="{{ route('admin.index') }}" class="nav-item-custom {{ request()->is('admin') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Administration
        </a>
        <a href="{{ route('admin.medecins-services.index') }}" class="nav-item-custom {{ request()->is('admin/medecins-services*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Médecins par Département
        </a>
        <a href="{{ route('admin.services.index') }}" class="nav-item-custom {{ request()->is('admin/services*') ? 'active' : '' }}">
            <i class="bi bi-hospital"></i> Services
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-item-custom {{ request()->is('admin/users*') ? 'active' : '' }}">
            <i class="bi bi-person"></i> Utilisateurs
        </a>
        @endif
    </div>
    
    <div class="user-section">
        <div style="color: white; margin-bottom: 10px;">
            <strong>{{ Auth::user()->name }}</strong><br>
            <small style="color: #adb5bd;">{{ Auth::user()->getRoleNames()->first() }}</small>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light" style="width: 100%;">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </button>
        </form>
    </div>
</div>

<div class="content-area">
    @yield('content')
</div>
@else
<div style="padding: 20px;">
    @yield('content')
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
