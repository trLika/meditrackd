@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">
            <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Rapports & Statistiques
        </h2>
        <a href="{{ route('admin.reports.export') }}" class="btn btn-danger shadow-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i> Exporter en PDF
        </a>
    </div>

    <div class="row g-4 mb-4">
        <!-- Par Service -->
        <div class="col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Répartition par Service</h5>
                </div>
                <div class="card-body">
                    <canvas id="serviceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Par Sexe -->
        <div class="col-lg-3">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Sexe</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Par Groupe Sanguin -->
        <div class="col-lg-3">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Groupe Sanguin</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="bloodChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Évolution des Consultations -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Évolution des Consultations (6 derniers mois)</h5>
                </div>
                <div class="card-body">
                    <canvas id="consultationChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Chart Service
    new Chart(document.getElementById('serviceChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($patientsByService->pluck('name')) !!},
            datasets: [{
                label: 'Nombre de patients',
                data: {!! json_encode($patientsByService->pluck('patients_count')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    // Chart Gender
    new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($patientsByGender->pluck('sexe')) !!},
            datasets: [{
                data: {!! json_encode($patientsByGender->pluck('total')) !!},
                backgroundColor: ['#36A2EB', '#FF6384']
            }]
        }
    });

    // Chart Blood
    new Chart(document.getElementById('bloodChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($patientsByBlood->pluck('groupe_sanguin')) !!},
            datasets: [{
                data: {!! json_encode($patientsByBlood->pluck('total')) !!},
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
            }]
        }
    });

    // Chart Consultations
    new Chart(document.getElementById('consultationChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyConsultations->pluck('month')) !!},
            datasets: [{
                label: 'Consultations',
                data: {!! json_encode($monthlyConsultations->pluck('total')) !!},
                borderColor: '#2ecc71',
                tension: 0.3,
                fill: true,
                backgroundColor: 'rgba(46, 204, 113, 0.1)'
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endsection
