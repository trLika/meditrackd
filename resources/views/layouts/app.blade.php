<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediTrackD votre système de gestion  intégré de dossiers médicaux</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
     <nav class="navbar navbar-expand-lg navbar-dark  shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">MediTrackD</a>

        <button class="navbar-toggler bg-success" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

       <div class="collapse navbar-collapse" id="mainNavbar">
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/') }}">Accueil</a>
        </li>

        @auth
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">Tableau de Bord</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('patients.index') }}">Patients</a>
            </li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link text-danger" style="text-decoration: none;">
                        Déconnexion
                    </button>
                </form>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link btn btn-success text-white px-3" href="{{ route('login') }}">Connexion</a>
            </li>
        @endauth
    </ul>
</div>
        </div>
    </div>
</nav>
     <main style="height: 100vh; overflow: hidden; display: flex; flex-direction: column;">
    <div style="flex: 1; overflow-y: auto; padding: 20px;">
        @yield('content')
    </div>
</main>
    </div>
   <script>
    // Configuration : 3 minutes = 180 000 millisecondes
    // On prévient l'utilisateur 30 secondes avant la fin (soit à 2min30)
    let warningTime = 150000;
    let logoutTime = 180000;

    //  Alerte de prévention
    setTimeout(function() {
        alert("🔒 Sécurité MediTrackD : Votre session va expirer dans 30 secondes par inactivité.");
    }, warningTime);

    // Redirection automatique vers le login à l'échéance
    setTimeout(function() {
        window.location.href = "{{ route('login') }}";
    }, logoutTime);
</script>
</body>
</html>
