@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="container mt-8 text-center text-success text-uppercase">
    <h2 class="mb-4 ">Tableau de Bord</h2>

    <div class="row">

        <div class="col-md-4 mb-4">

            <div class="card bg-primary text-white shadow">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-uppercase"> nombre Total de Patients enregistré</h6>

                            <h1 class="display-4 fw-bold ">{{ $totalPatients }}</h1>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="col-md-4">

    <div class="card bg-danger text-white shadow">
        <a href="{{ route('patients.index', ['filter' => 'critique']) }} " class="text-decoration-none text-white">
        <div class="card-body">
            <h5 class="card-title">CAS CRITIQUES</h5>
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold">{{ $criticalCases }}</h2>
                <i class="bi bi-exclamation-triangle-fill fs-1"></i>
            </div>
            </a>
        </div>
    </div>
</div>

        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">nombre de consultations effectuée Aujourd'hui</h6>
                            <h1 class="display-4 fw-bold ">{{ $consultationsToday }}</h1>
                        </div>
                        <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>
<div class="row mt-4">
<div class="col-md-6 mb-4">
    <div class="card shadow-sm border-0 h-100 "> <div class="card-header bg-danger fw-bold text-dark">Répartition par Groupe Sanguin</div>
        <div class="card-body  d-flex justify-content-center align-items-center">
       <div style="max-height: 300px; width: 100%;">
        <canvas id="bloodChart"></canvas>
        </div>
    </div>
</div>
</div>

        <div class="col-md-6 mb-4">
         <div class="card shadow border-0 h-100">
    <div class="card-header bg-dark fw-bold d-flex justify-content-between align-items-center text-white">
        <h5 class="mb-0">
            <i class="bi bi-people me-2 text-danger"></i> Les derniers patients enregistrés
        </h5>
        <span class="badge bg-danger rounded-pill">{{ $totalPatients }} au total</span>
    </div>

    <div class="card-body scrollable-module">
        <table class="table table-hover mb-0">
            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                <tr>
                    <th>NOM</th>
                    <th>PRÉNOM</th>
                    <th>ÂGE</th>
                    <th>SEXE</th>
                    <th>DATE D'AJOUT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentPatients as $patient)
                <tr class="clickable-row" data-href="{{ route('patients.show', $patient->id) }}" style="cursor: pointer;">
                    <td>
                        <strong>{{ $patient->nom }}</strong>  </td>
                    <td>{{ $patient->prenom }}</td>
                    <td>{{ $patient->age }} ans</td>
                    <td>

                    <span class="badge {{ $patient->sexe == 'M' ? 'bg-info' : 'bg-pink' }} text-dark">
                            {{ $patient->sexe }}
                        </span>
                    </td>
                    <td><small class="text-muted">{{ $patient->created_at->format('d/m/Y') }}</small></td>
                      <td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
        </table>
                <div class="col-md-6">
<script>
    const ctx = document.getElementById('bloodChart');
    new Chart(ctx, {
        type: 'pie', // Un graphique en camembert
        data: {
            labels: {!! json_encode($groupesSanguins->pluck('groupe_sanguin')) !!},
            datasets: [{
                data: {!! json_encode($groupesSanguins->pluck('total')) !!},
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff']
            }]
        }
    });

</script>

                </div>
            </div>
        </div>
    </div>
</div>
<!--niveau de tracabilite sur le dashboard-->
@if(auth()->user()->role === 'admin')
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i> Activités Récentes</h5>
                 <span class="badge bg-secondary text-danger">{{ $recentLogs->count() }} logs affichés</span>
            </div>

<!--Affichage du barre scrollante-->
            <div class="card-body bg-secondary scrollable-module">
                <table class="table table-hover align-middle">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: index: 1;">
                        <tr>
                            <th>Utilisateur</th>
                            <th>Action</th>
                            <th>Patient</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                            <tr>
                                <td><strong>{{ $log->user->name ?? 'Utilisateur inconnu' }}</strong></td>
                                <td><span class="badge bg-secondary text-info">{{ $log->action }}</span></td>
                                <td>{{ $log->patient_name }}</td>
                                <td>{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Aucune activité enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
<script>
document.addEventListener("DOMContentLoaded", function() {
    const rows = document.querySelectorAll(".clickable-row");
    rows.forEach(row => {
        row.addEventListener("click", function() {
            window.location.href = this.dataset.href;
        });
    });
});
</script>
@endsection
