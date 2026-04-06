@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class ="text-dark text-uppercase fw-bold">Tableau de Bord</h2>
        <span class="badge bg-light text-dark shadow-sm p-2">{{ now()->format('d/m/Y H:i') }}</span>
    </div>

    <!--Affichage des statistiques-->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white shadow h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase opacity-75">Le total de patients enregistrés</h6>
                            <h1 class="display-5 fw-bold">{{ $totalPatients }}</h1>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
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
                            <h6 class="text-uppercase opacity-75" >Les cas critiques</h6>
                            <h1 class="display-5 fw-bold">{{ $criticalCases }}</h1>
                        </div>
                        <i class="bi bi-exclamation-triangle-fill fs-1 opacity-50"></i>
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
                            <h6 class="text-uppercase opacity-75">Consultations effectuées / Jour</h6>
                            <h1 class="display-5 fw-bold">{{ $consultationsToday }}</h1>
                        </div>
                        <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                    </div>
                    <a href="{{ route('patients.index', ['filter' => 'today']) }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-4 mb-4">

        <div class="col-lg-5">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-danger fw-bold border-0 pt-3 text-white d-flex align-items-center">
                    <i class="bi bi-pie-chart-fill me-2 text-white"></i> Répartition par groupes sanguins
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div style="height: 300px; width: 100%;">
                        <canvas id="bloodChart"></canvas><!-- Graphique Chart.js  -->
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-7">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i> Derniers patients enregistrés</h5>
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
    @if(auth()->user()->role === 'admin')
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

<!-- STYLES INTERNE -->
<style>
    .stat-card { transition: all 0.3s ease; border: none; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.15) ; }
    .bg-pink { background-color: #f8bbd0; }
    .sticky-top { z-index: 10; background-color: white; }
</style>

<!-- JS INTERNE -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Graphique Sanguin
    const ctx = document.getElementById('bloodChart');
    if(ctx) {
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($groupesSanguins->pluck('groupe_sanguin')) !!},
                datasets: [{
                    data: {!! json_encode($groupesSanguins->pluck('total')) !!},
                    backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff', '#4caf50', '#ff9800', '#795548']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Rendre les lignes du tableau cliquables
    const rows = document.querySelectorAll(".clickable-row");
    rows.forEach(row => {
        row.addEventListener("click", function() {
            window.location.href = this.dataset.href;
        });
    });
});
</script>
@endsection
