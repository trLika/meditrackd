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
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark fw-bold d-flex justify-content-beetween align-items-center text-danger">
                   Les  derniers patients enregistrés
                   <a href="{{ route('patients.index')}}" class="btn btn-outline-success border-0">Afficher tout</a>
                </div>
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
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentPatients as $patient)
                            <a href="{{ route('patients.show', $patient->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                           <i class="bi bi-person-circle me-2 text-secondary"></i>
                            {{ $patient->nom }} {{ $patient->prenom }}
                            <span class="badge bg-light text-dark rounded-pill border">
                                {{ $patient->created_at->diffForHumans() }}
                            </span>
                            </a>
                        @empty
                            <div class="p-4 text-center text-muted">Aucun patient pour le moment.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
