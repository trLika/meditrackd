@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediTrackD - Gestion des Patients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">


        


    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-secondary">Liste des Patients</h2>
            <button class="btn btn-danger shadow-sm">
                <i class="bi bi-person-plus-fill"></i> Nouveau Patient
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nom & Prénom</th>
                            <th>Téléphone</th>
                            <th>Groupe Sanguin</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
    <tr>
        <td>{{ $patient->id }}</td>
        <td><strong>{{ $patient->nom }}</strong> {{ $patient->prenom }}</td>
        <td>{{ $patient->telephone }}</td>
        <td><span class="badge bg-info">{{ $patient->groupe_sanguin }}</span></td>
        <td>
            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
            <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
@endsection
