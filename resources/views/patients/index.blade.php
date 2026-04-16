@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Messages flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success m-0">
            <i class="bi bi-people-fill me-2"></i>Liste des Patients
        </h2>
        <a href="{{ route('patients.create') }}" class="btn btn-success rounded-pill shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Ajouter un patient
        </a>
    </div>

    <!-- Filtre cas critique -->
    @if(request()->has('critique'))
        <div class="alert alert-danger py-2 px-3 mb-3 d-flex justify-content-between align-items-center shadow-sm">
            <span>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Liste des cas critiques</strong>
            </span>
            <a href="{{ route('patients.index') }}" class="btn btn-sm btn-link text-danger p-0 text-decoration-none">
                Annuler le filtre
            </a>
        </div>
    @endif

    <!-- Barre de recherche -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <form action="{{ route('patients.index') }}" method="GET" class="input-group shadow-sm">
                <input type="text" name="search" class="form-control" placeholder="Rechercher un patient..." value="{{ request('search') }}">
                <button class="btn btn-info text-white" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Tableau des patients -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if($patients->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-folder-x display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun patient trouvé</h5>
                    <p class="text-muted">Essayez de modifier votre recherche ou ajoutez un nouveau patient.</p>
                    <a href="{{ route('patients.create') }}" class="btn btn-success">
                        <i class="bi bi-person-plus me-2"></i>Ajouter un patient
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th class="fw-bold">ID</th>
                                <th class="fw-bold">Nom & Prénom</th>
                                <th class="fw-bold">Sexe</th>
                                <th class="fw-bold">Téléphone</th>
                                <th class="fw-bold">Adresse</th>
                                <th class="fw-bold">Groupe Sanguin</th>
                                <th class="text-center fw-bold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                                <tr>
                                    <td class="fw-bold">{{ $patient->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 12px;">
                                                {{ strtoupper(substr($patient->nom, 0, 1)) }}
                                            </div>
                                            <span class="fw-bold">{{ $patient->nom }} {{ $patient->prenom }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $patient->sexe == 'M' ? 'primary' : 'pink' }} text-white">
                                            {{ $patient->sexe == 'M' ? 'M' : 'F' }}
                                        </span>
                                    </td>
                                    <td>{{ $patient->telephone }}</td>
                                    <td>{{ $patient->adresse }}</td>
                                    <td>
                                        <span class="badge bg-danger text-white">
                                            {{ $patient->groupe_sanguin }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('patients.show', $patient->id) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if(auth()->user()->role !== 'stagiaire')
                                                <a href="{{ route('patients.edit', $patient->id) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('consultations.create', $patient->id) }}"
                                                   class="btn btn-sm btn-outline-info" title="Ajouter une consultation">
                                                    <i class="bi bi-plus-circle"></i>
                                                </a>
                                                <form action="{{ route('patients.destroy', $patient->id) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
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
                </div>
                
                <!-- Pagination -->
                <div class="p-3 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            {{ $patients->count() }} patient(s) trouvé(s)
                        </small>
                        {{ $patients->appends(['search' => request('search')])->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
