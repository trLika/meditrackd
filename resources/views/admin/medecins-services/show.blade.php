@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.medecins-services.index') }}">Médecins par Département</a></li>
                    <li class="breadcrumb-item active">{{ $service->name }}</li>
                </ol>
            </nav>
            <h2>
                <i class="bi bi-hospital"></i> 
                Service: {{ $service->name }}
            </h2>
        </div>
        <a href="{{ route('admin.medecins-services.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
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

    <div class="row">
        <!-- Médecins assignés -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill"></i> 
                        Médecins assignés ({{ $service->users->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($service->users->count() > 0)
                        <div class="list-group">
                            @foreach($service->users as $medecin)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $medecin->name }}</h6>
                                        <small class="text-muted">{{ $medecin->email }}</small>
                                    </div>
                                    <form action="{{ route('admin.medecins-services.remove', [$service->id, $medecin->id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Retirer Dr. {{ $medecin->name }} de ce service ?')">
                                            <i class="bi bi-person-dash"></i> Retirer
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-person-x" style="font-size: 3rem;"></i>
                            <p class="mt-3">Aucun médecin assigné à ce service</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Assigner un médecin -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-plus"></i> 
                        Assigner un médecin
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.medecins-services.assign', $service->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Sélectionner un médecin</label>
                            <select name="medecin_id" class="form-select" required>
                                <option value="">Choisir un médecin...</option>
                                @forelse($medecinsDisponibles as $medecin)
                                    <option value="{{ $medecin->id }}">{{ $medecin->name }} ({{ $medecin->email }})</option>
                                @empty
                                    <option value="" disabled>Tous les médecins sont déjà assignés</option>
                                @endforelse
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100" @if($medecinsDisponibles->count() === 0) disabled @endif>
                            <i class="bi bi-person-plus"></i> Assigner au service {{ $service->name }}
                        </button>
                    </form>

                    @if($medecinsDisponibles->count() === 0)
                        <div class="alert alert-info mt-3">
                            <small>
                                <i class="bi bi-info-circle"></i>
                                Tous les médecins sont déjà assignés à ce service. 
                                <a href="{{ route('admin.medecins-services.non-assignes') }}">Voir les médecins non assignés</a>
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informations sur le service -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> 
                        Informations sur le service
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nom:</strong> {{ $service->name }}</p>
                            <p><strong>Description:</strong> {{ $service->description ?? 'Aucune description' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Nombre de médecins:</strong> {{ $service->users->count() }}</p>
                            <p><strong>Capacité:</strong> Non définie</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
