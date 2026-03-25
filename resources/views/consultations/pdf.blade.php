<!DOCTYPE html>
<html>
<head>
    <title>Ordonnance MediTrackD</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .patient-info { margin-top: 30px; }
        .content { margin-top: 50px; min-height: 300px; }
        .footer { margin-top: 50px; font-size: 10px; text-align: center; color: gray; }
        font-family: sans-serif;
        padding: 30px;
        h1{
            color:red;
            margin-bottom: 5px;
        }
        .header p{
            font-size: 14px;
            color: get_parent_class($this) === 'header' ? '#555' : '#7ffa4e';
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>MEDITRACKD</h1>
        <p>Logiciel de Gestion integré des dossiers médicaux | Dossier du  Patient</p>
    </div>

    <div class="patient-info">
        <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}</p>
        <p><strong>Patient :</strong> {{ $consultation->patient->nom }} {{ $consultation->patient->prenom }}</p>
    </div>

    <div class="content">
        <h3>ORDONNANCE</h3>
        <p><strong>Diagnostic :</strong><br> {{ $consultation->diagnostic }}</p>
        <br>
        <p><strong>Traitement prescrit :</strong><br> {{ $consultation->traitement }}</p>
    </div>

    <div class="footer">
        <p>Document généré automatiquement par MediTrackD - Plateforme de gestion de L3 Ingenierie</p>
    </div>
</body>
</html>
