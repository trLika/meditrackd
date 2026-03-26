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
            <h2 class="fw-bold text-success">Liste des Patients</h2>

             

   <!-- {{-- DEBUT DU BLOC A AJOUTER --}}-->
    @if(auth()->user()->role !== 'stagiaire')
        <a href="{{ route('patients.create') }}" class="btn btn-success rounded-pill">
            <i class="bi bi-person-plus-fill me-2"></i> Ajouter un Patient
        </a>
    @endif
    <!--{{-- FIN DU BLOC --}}-->

</div>

        </div>

            @if(request('search'))
                <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary ms-2">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>
   <div class="row mb-3 d-flex justify-content-center">
    <div class="col-md-3">
        <form action="{{ route('patients.index') }}" method="GET" class="d-flex shadow-sm p-1 bg-white rounded">
            <input type="text"
                   name="search"
                   class="form-control border-0"
                   placeholder="saisissez  nom, prénom ou téléphone..."
                   value="{{ request('search') }}">

            <button type="submit" class="btn btn-info ms-2 px-4">
                <i class="bi bi-search"></i> Rechercher
            </button>

            @if(request('search'))
                <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-x-circle"></i>
                </a>
            @endif
        </form>
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
        <span class="badge bg-info text-dark">{{ $patient->groupe_sanguin }}</span>
    </td>
    <td class="text-center">
        <div class="d-flex justify-content-center gap-1">

        <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-eye"></i></a>
@if(auth()->user()->role !== 'stagiaire')
            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-warning">
            <i class="bi bi-pencil"></i></a>
       <a href="{{ route('consultations.create', $patient->id) }}" class="btn btn-sm btn-outline-info">
    <i class="bi bi-plus-circle"></i>
</a>
        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')">
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

</body>
</html>
@endsection
