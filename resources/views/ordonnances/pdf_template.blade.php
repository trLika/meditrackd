<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ordonnance Numérique - {{ $ordonnance->patient->nom }}</title>
    <style>
        /* Base et Polices */
        body {
            font-family: 'Times New Roman', Times, serif; /* Pour le côté traditionnel */
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 10px;
        }

        /* Couleurs du modèle */
        .color-title { color: #1e7a4f; } /* Vert foncé pour le titre principal */
        .color-info { color: #004085; }  /* Bleu foncé pour les infos patient/date */

        /* En-tête */
        .header {
            text-align: center;
            border-bottom: 2px solid #ccc;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .cabinet-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .cabinet-address {
            font-size: 14px;
            color: #555;
        }

        /* Titre du document */
        .doc-title {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }

        /* Section Informations (Date et Patient) */
        .info-section {
            margin-bottom: 30px;
            font-size: 16px;
        }
        .info-line {
            margin-bottom: 8px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 80px;
        }

        /* Corps de la prescription */
        .prescription-body {
            font-size: 18px;
            min-height: 450px; /* Espace pour les médicaments */
            line-height: 1.8;
            white-space: pre-line; /* Conserve les sauts de ligne */
            padding-left: 10px;
        }

        /* Bas de page et Signature */
        .footer {
            margin-top: 50px;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .signature-box {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>

    <div class="header color-title">
        <div class="cabinet-name">CABINET MÉDICAL DE SOINS « MEDITRACKD »</div>
        <div class="cabinet-address">
            Bamako, Mali • Tél: (+223) 00 00 00 00<br>
            Prescription électronique {{ $ordonnance->user->name }}
        </div>
    </div>

    <div class="doc-title color-title">Ordonnance Médicale</div>

    <div class="info-section color-info">
        <div class="info-line">
            <span class="label">Date :</span>
            {{ \Carbon\Carbon::parse($ordonnance->date_prescription)->format('d/m/Y') }}
        </div>
        <div class="info-line">
            <span class="label">Patient :</span>
            {{ $ordonnance->patient->nom }} {{ $ordonnance->patient->prenom }}
            ({{ $ordonnance->patient->age }} ans)
        </div>
    </div>

    <div class="prescription-body">
        <strong>Médicaments et Posologie :</strong><br><br>
        {{ $ordonnance->contenu }}
    </div>

    <div class="footer">
        <div class="signature-box">
            Signature et Cachet :
            <br><br><br>
            __________________________
        </div>
        <div style="text-align:center; color:#888;">
            Ce document est une ordonnance numérique générée par le système MediTrackD.
        </div>
    </div>

</body>
</html>
