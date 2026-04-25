<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #dc3545; padding-bottom: 10px; margin-bottom: 30px; }
        .header h1 { color: #dc3545; margin: 0; }
        .stats-box { width: 100%; margin-bottom: 20px; }
        .stats-box td { padding: 10px; border: 1px solid #ddd; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f8f9fa; color: #dc3545; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MediTrackD</h1>
        <h3>Rapport Statistique Administratif</h3>
        <p>Généré le : {{ $date }}</p>
    </div>

    <h4>Résumé Global</h4>
    <table class="stats-box">
        <tr>
            <td><strong>Total Patients :</strong> {{ $totalPatients }}</td>
            <td><strong>Total Consultations :</strong> {{ $totalConsultations }}</td>
        </tr>
    </table>

    <h4>Répartition par Service</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Service</th>
                <th>Nombre de Patients</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patientsByService as $service)
            <tr>
                <td>{{ $service->name }}</td>
                <td>{{ $service->patients_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Données Démographiques (Sexe)</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Sexe</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patientsByGender as $g)
            <tr>
                <td>{{ $g->sexe == 'M' ? 'Masculin' : 'Féminin' }}</td>
                <td>{{ $g->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Document confidentiel généré par le système SGIDM - MediTrackD
    </div>
</body>
</html>
