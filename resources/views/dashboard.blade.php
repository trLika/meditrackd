@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-dark text-uppercase fw-bold">
                Tableau de Bord
                {{-- DEBUG: {{ auth()->user()->name }} - Role: {{ auth()->user()->getRoleNames()->first() }} - isAdmin: {{ auth()->user()->hasRole('admin') ? 'YES' : 'NO' }} --}}
                @if(auth()->user()->name !== 'Administrateur' && !auth()->user()->hasRole('admin'))
                    <small class="text-muted fs-6">- Mes Services</small>
                @endif
            </h2>
            @if(auth()->user()->name !== 'Administrateur' && !auth()->user()->hasRole('admin') && isset($userServices) && $userServices->count() > 0)
                <div class="mt-2">
                    @foreach($userServices as $service)
                        <span class="badge bg-primary me-1">{{ $service->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>
        <span class="badge bg-pink shadow-sm p-2">{{ now()->format('d/m/Y H:i') }}</span>
    </div>

    <!--Affichage des statistiques-->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white shadow h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase opacity-75">
                                @if(auth()->user()->name === 'Administrateur' || auth()->user()->hasRole('admin')) 
                                    Total patients enregistrés
                                @else 
                                    Mes patients
                                @endif
                            </h6>
                            <h1 class="display-5 fw-bold">{{ $totalPatients }}</h1>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50 text-dark"></i>
                    </div>
                    <a href="{{ route('patients.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-danger text-white shadow h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase opacity-75">
                                @if(auth()->user()->name === 'Administrateur' || auth()->user()->hasRole('admin')) 
                                    Cas critiques
                                @else 
                                    Mes cas critiques
                                @endif
                            </h6>
                            <h1 class="display-5 fw-bold">{{ $criticalCases }}</h1>
                        </div>
                        <i class="bi bi-exclamation-triangle-fill fs-1 opacity-50 text-warning"></i>
                    </div>
                    <a href="{{ route('patients.index', ['critique' => 1]) }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-info text-white shadow h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase opacity-75">Consultations aujourd'hui</h6>
                            <h1 class="display-5 fw-bold">{{ $consultationsToday }}</h1>
                        </div>
                        <i class="bi bi-calendar-check fs-1 opacity-50 text-primary center"></i>
                    </div>
                    <a href="{{ route('consultations.index', ['today' => 1]) }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-4 mb-4">

        <div class="col-lg-5">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-danger fw-bold border-0 pt-3 text-white d-flex align-items-center">
                    <i class="bi bi-pie-chart-fill me-2 text-secondary"></i> Répartition par groupes sanguins
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div style="height: 300px; width: 100%;">
                       <canvas id="bloodChart"
    data-labels='{!! json_encode($groupesSanguins->pluck("groupe_sanguin")) !!}'
    data-values='{!! json_encode($groupesSanguins->pluck("total")) !!}'>
</canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-7">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2 text-dark"></i> 
                        @if(auth()->user()->name === 'Administrateur' || auth()->user()->hasRole('admin')) 
                            Derniers patients enregistrés
                        @else 
                            Mes derniers patients
                        @endif
                    </h5>
                    <span class="badge bg-danger rounded-pill">{{ $totalPatients }} patients</span>
                </div>
                <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>NOM</th>
                                <th>PRÉNOM</th>
                                <th>ÂGE</th>
                                <th>SEXE</th>
                                <th>DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPatients as $patient)
                            <tr class="clickable-row" data-href="{{ route('patients.show', $patient->id) }}" style="cursor: pointer;">
                                <td><strong>{{ $patient->nom }}</strong></td>
                                <td>{{ $patient->prenom }}</td>
                                <td>{{ $patient->age }} ans</td>
                                <td>
                                    <span class="badge {{ $patient->sexe == 'M' ? 'bg-info' : 'bg-pink' }} text-dark">
                                        {{ $patient->sexe }}
                                    </span>
                                </td>
                                <td><small class="text-muted">{{ $patient->created_at->format('d/m/Y') }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Affichage des logs récents pour les admins-->
    @if(auth()->user()->name === 'Administrateur' || auth()->user()->hasRole('admin'))
    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2 text-info"></i> Activités Récentes du Système</h5>
                    <span class="badge bg-info text-dark">{{ $recentLogs->count() }} derniers logs</span>
                </div>
                <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th>Utilisateur</th>
                                <th>Action effectuée</th>
                                <th>Patient concerné</th>
                                <th>Horodatage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                            <tr>
                                <td class="fw-bold">{{ $log->user->name ?? 'Système' }}</td>
                                <td><span class="badge bg-info text-dark border">{{ $log->action }}</span></td>
                                <td>{{ $log->patient_name }}</td>
                                <td class="text-muted small">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Aucun historique disponible.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>




@endsection
