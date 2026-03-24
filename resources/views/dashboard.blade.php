@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center"> <div class="col-md-8 text-center mb-5">
            <h1 class="display-5 fw-bold text-danger shadow-sm p-3 bg-white rounded">
                Tableau de Bord - MediTrackD
            </h1>
        </div>
    </div>

    <div class="row justify-content-center"> <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="bg-success py-2"></div>

                <div class="card-body p-5 text-center">
                    <h5 class="text-uppercase text-muted fw-bold mb-4" style="letter-spacing: 1px;">
                        Nombre total de patients enregistrés
                    </h5>

                    <div class="display-1 fw-bold text-danger mb-4">
                        {{ $totalPatients ?? $nbPatients }}
                    </div>

                    <hr class="my-4">

                    <a href="{{ route('patients.index') }}" class="btn btn-success btn-lg px-5 rounded-pill shadow-sm">
                        <i class="fas fa-users me-2"></i> Accéder à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
