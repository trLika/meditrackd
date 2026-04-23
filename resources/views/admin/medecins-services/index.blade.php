@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="bi bi-people"></i> 
            Médecins par Département
        </h2>
        <div>
            <a href="{{ route('admin.medecins-services.non-assignes') }}" class="btn btn-warning me-2">
                <i class="bi bi-person-exclamation"></i> Médecins non assignés
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignationModal">
                <i class="bi bi-person-plus"></i> Assignation rapide
            </button>
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['total_services'] }}</h4>
                    <small>Services</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['total_medecins'] }}</h4>
                    <small>Médecins total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['medecins_assignes'] }}</h4>
                    <small>Médecins assignés</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['services_vides'] }}</h4>
                    <small>Services vides</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Barre de recherche --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" action="{{ route('admin.medecins-services.search') }}">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Rechercher un médecin ou un service..." value="{{ $query ?? '' }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-search"></i> Rechercher
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4 text-end">
            @if(isset($query))
                <a href="{{ route('admin.medecins-services.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Effacer recherche
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Liste des services avec leurs médecins --}}
    <div class="row">
        @forelse($services as $service)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-hospital"></i> {{ $service->name }}
                        </h5>
                        <span class="badge bg-primary">{{ $service->users->count() }} médecin(s)</span>
                    </div>
                    <div class="card-body">
                        @if($service->users->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($service->users as $medecin)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $medecin->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $medecin->email }}</small>
                                        </div>
                                        <div>
                                            <form action="{{ route('admin.medecins-services.remove', [$service->id, $medecin->id]) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Retirer Dr. {{ $medecin->name }} du service {{ $service->name }} ?')">
                                                    <i class="bi bi-person-dash"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.medecins-services.show', $service->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-3">
                                <i class="bi bi-person-x" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">Aucun médecin assigné</p>
                                <small>
                                    <a href="{{ route('admin.medecins-services.show', $service->id) }}" class="text-decoration-none">
                                        Assigner des médecins
                                    </a>
                                </small>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.medecins-services.show', $service->id) }}" class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-gear"></i> Gérer ce service
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i>
                    Aucun service trouvé. 
                    <a href="{{ route('admin.services.create') }}" class="alert-link">Créer un service</a>
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- Modal d'assignation rapide --}}
<div class="modal fade" id="assignationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assignation Rapide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.medecins-services.assign') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Service</label>
                            <select name="service_id" class="form-select" required>
                                <option value="">Sélectionner un service...</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Médecin</label>
                            <select name="medecin_id" class="form-select" required>
                                <option value="">Sélectionner un médecin...</option>
                                @foreach($medecins as $medecin)
                                    <option value="{{ $medecin->id }}">{{ $medecin->name }} ({{ $medecin->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-person-plus"></i> Assigner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
