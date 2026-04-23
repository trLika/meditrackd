@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="bi bi-shield-lock"></i> 
            Tableau de Bord Administration
        </h2>
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
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['total_medecins'] }}</h4>
                    <small>Médecins</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['medecins_assignes'] }}</h4>
                    <small>Médecins Assignés</small>
                    @if($stats['taux_assignation'] > 0)
                        <div class="small">{{ $stats['taux_assignation'] }}%</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['total_utilisateurs'] }}</h4>
                    <small>Utilisateurs Total</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Indicateurs additionnels --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-primary">{{ $stats['services_vides'] }}</h5>
                    <small class="text-muted">Services sans médecins</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-success">{{ $stats['taux_assignation'] }}%</h5>
                    <small class="text-muted">Taux d'assignation</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Modules de gestion --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-hospital text-primary"></i> Gestion des Services
                    </h5>
                    <p class="card-text">Gérez les départements et services hospitaliers.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.services.index') }}" class="btn btn-primary">
                            <i class="bi bi-list"></i> Voir tous les services
                        </a>
                        <a href="{{ route('admin.services.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-plus"></i> Ajouter un service
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-people-fill text-success"></i> Assignation Médecins
                    </h5>
                    <p class="card-text">Assignez les médecins aux services hospitaliers.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.medecins-services.index') }}" class="btn btn-success">
                            <i class="bi bi-people"></i> Voir les assignations
                        </a>
                        <a href="{{ route('admin.medecins-services.non-assignes') }}" class="btn btn-outline-success">
                            <i class="bi bi-person-exclamation"></i> Médecins non assignés
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-person text-info"></i> Gestion Utilisateurs
                    </h5>
                    <p class="card-text">Gérez les comptes utilisateurs et leurs accès.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-info">
                            <i class="bi bi-people"></i> Voir tous les utilisateurs
                        </a>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-info">
                            <i class="bi bi-plus"></i> Ajouter un utilisateur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge"></i> Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Assignation rapide</h6>
                            <p class="text-muted">Assigner rapidement un médecin à un service</p>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#quickAssignModal">
                                <i class="bi bi-person-plus"></i> Assignation Rapide
                            </button>
                        </div>
                        <div class="col-md-6">
                            <h6>Création rapide</h6>
                            <p class="text-muted">Ajouter rapidement un nouveau service ou utilisateur</p>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.services.create') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-hospital"></i> Service
                                </a>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-outline-info">
                                    <i class="bi bi-person"></i> Utilisateur
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal d'assignation rapide --}}
<div class="modal fade" id="quickAssignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assignation Rapide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.medecins-services.assign') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="service_id" class="form-label">Service</label>
                        <select name="service_id" id="service_id" class="form-select" required>
                            <option value="">Choisir un service</option>
                            @foreach(\App\Models\Service::all() as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="medecin_id" class="form-label">Médecin</label>
                        <select name="medecin_id" id="medecin_id" class="form-select" required>
                            <option value="">Choisir un médecin</option>
                            @foreach(\App\Models\User::role('medecin')->get() as $medecin)
                            <option value="{{ $medecin->id }}">{{ $medecin->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Assigner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
