@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4">
                <i class="bi bi-hospital"></i> 
                Bienvenue dans MediTrackD
            </h1>
            <p class="lead">Système de gestion médicale complet pour votre hôpital</p>
            
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3>{{ \App\Models\Patient::count() }}</h3>
                            <p>Patients enregistrés</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3>{{ \App\Models\User::role('medecin')->count() }}</h3>
                            <p>Médecins disponibles</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3>{{ \App\Models\Service::count() }}</h3>
                            <p>Services actifs</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <h3>Actions Rapides</h3>
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('patients.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-person-plus"></i> Nouveau Patient
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('patients.index') }}" class="btn btn-info w-100 mb-2">
                            <i class="bi bi-people"></i> Voir Patients
                        </a>
                    </div>
                    @if(Auth::user()->hasRole('administrateur'))
                    <div class="col-md-3">
                        <a href="{{ route('admin.index') }}" class="btn btn-warning w-100 mb-2">
                            <i class="bi bi-gear"></i> Administration
                        </a>
                    </div>
                    @endif
                    @if(Auth::user()->hasRole('medecin'))
                    <div class="col-md-3">
                        <a href="{{ route('consultations.index') }}" class="btn btn-success w-100 mb-2">
                            <i class="bi bi-file-medical"></i> Consultations
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Informations Système</h5>
                </div>
                <div class="card-body">
                    <p><strong>Utilisateur connecté:</strong></p>
                    <p>{{ Auth::user()->name }}</p>
                    <p><strong>Rôle:</strong> {{ Auth::user()->getRoleNames()->first() }}</p>
                    <p><strong>Date:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                    
                    @if(Auth::user()->hasRole('administrateur'))
                    <hr>
                    <h6>Accès Administration</h6>
                    <a href="{{ route('admin.index') }}" class="btn btn-sm btn-warning w-100">
                        <i class="bi bi-gear"></i> Panneau Admin
                    </a>
                    <a href="{{ route('admin.medecins-services.index') }}" class="btn btn-sm btn-info w-100 mt-2">
                        <i class="bi bi-people-fill"></i> Médecins par Service
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
