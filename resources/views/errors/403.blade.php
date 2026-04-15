<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Refusé</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        h1 { font-size: 48px; color: #e74c3c; margin-bottom: 10px; }
        h2 { margin-bottom: 20px; }
        p { color: #666; margin-bottom: 30px; }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn:hover { background-color: #2980b9; }
    </style>
</head>
<body>
    <div class="container">
        <h1>403</h1>
        <h2>Accès restreint</h2>
        <p>Oups ! Il semble que vous n'ayez pas les autorisations nécessaires pour accéder à cette section. Veuillez contacter un administrateur si vous pensez qu'il s'agit d'une erreur.</p>
        <a href="{{ route('dashboard') }}" class="btn">Retour au tableau de bord</a>
    </div>
</body>
</html>
