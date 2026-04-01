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
 @extends('layouts.app')

@section('content')
<div class="container-fluid py-3" style="height: 100vh; display: flex; flex-direction: column;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-success m-0">Liste des Patients</h2>

            <a href="{{ route('patients.create') }}" class="btn btn-success rounded-pill shadow-sm">
                <i class="bi bi-person-plus-fill me-1"></i> Ajouter
            </a>

    </div>

 <div class="row justify-content-center mb-3">
        <div class="col-md-5">
            <form action="{{ route('patients.index') }}" method="GET" class="input-group shadow-sm">
                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                <button class="btn btn-info text-white" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

@if(request()->has('critique'))
    <div class="alert alert-danger py-2 px-3 mb-2 d-flex justify-content-between align-items-center shadow-sm small">
        <span><i class="bi bi-exclamation-triangle-fill"></i>
        <strong >Ceci est la liste de vos cas critique</strong></span>
        <a href="{{ route('patients.index') }}"
        class="btn btn-xs btn-link text-danger p-0 text-decoration-none">Annuler le filtre </a>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0 color"> @if($patients->isEmpty())
            <div class="text-center py-4">
                <i class="bi bi-folder-x  display-4 text-muted"></i>
                <p class="mb-2">Aucun patient trouvé.</p>
                <a href="{{ route('patients.index') }}" class="btn btn-sm btn-success">Voir tout</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0">
                    </table>
            </div>
        @endif
    </div>
</div>


<div class="card shadow-sm" style="height: auto; max-height: 80vh; overflow: hidden; display: flex; flex-direction: column;">
        <div class="table-responsive" style="overflow-y: auto; flex: 1;">
            <table class="table table-hover align-middle mb-0">

                <table class="table table-striped table-hover mb-0">
                    <thead class="table-ligh text-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Nom & Prénom</th>
                            <th>Sexe</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>Groupe Sanguin</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach($patients as $patient)
<tr>
    <td>{{ $patient->id }}</td>
    <td><strong>{{ $patient->nom }}</strong> {{ $patient->prenom }}</td>
    <td>{{ $patient->sexe ?? '-' }}</td>
    <td>{{ $patient->telephone }}</td>
    <td>{{ $patient->adresse ?? '-' }}</td>
    <td>
    {{ $patient->groupe_sanguin }}
    </td>
    <td class="text-center ">
        <div class="d-flex justify-content-center align-items-center gap-2">
<a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-outline-primary btn-sm" title="Voir les détails">
            <i class="bi bi-eye"></i></a>
 @if(auth()->user()->role !== 'stagiaire')
            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-warning btn-sm" title="Modifier">
            <i class="bi bi-pencil"></i></a>
       <a href="{{ route('consultations.create', $patient->id) }}"
       class="btn btn-sm btn-outline-info btn-sm" title="Ajouter une consultation">
    <i class="bi bi-plus-circle"></i>
</a>
        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger btn-sm" title="Supprimer" onclick="return confirm('Supprimer ?')">
                <i class="bi bi-trash"></i>
</button>
        </form>

@endif
        </div>
    </td>
</tr>
@endforeach
                    </tbody>
                </table>

                <div class="mt-3">
    {{ $patients->appends(['search' => request('search')])->links() }}
</div>
            </div>
        </div>
    </div>
            </table>
        </div>
    </div>
</div>


        <div class="card shadow-sm ">
            <div class="card-body p-0">

             @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>
                {{ session('success') }}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif




</body>
</html>
@endsection
