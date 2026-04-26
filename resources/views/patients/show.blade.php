@extends('layouts.app')

@section('content')
<div class="container-fluid pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white fw-bold m-0" style="text-shadow: 0 4px 10px rgba(0,0,0,0.3);">
            <i class="bi bi-person-bounding-box me-2 text-info"></i>Dossier Patient
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('patients.index') }}" class="btn btn-outline-danger px-3 shadow-sm">
                <i class="bi bi-arrow-left  "></i> Retour
            </a>
            @if(auth()->user()->name === 'Administrateur' || auth()->user()->hasAnyRole(['admin', 'administrateur']) || auth()->user()->hasRole('medecin') || auth()->user()->role === 'medecin' || auth()->user()->role === 'admin')
                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary px-3 shadow-sm text-white">
                    <i class="bi bi-pencil-square"></i> Modifier
                </a>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-dashboard-style h-100">
                <div class="card-header border-0 bg-header-dark">
                    <h5 class="mb-0 text-info fw-bold small text-uppercase"><i class="bi bi-fingerprint me-2"></i>Identité du Patient</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-info d-flex align-items-center justify-content-center shadow" style="width: 60px; height: 60px;">
                            <span class="h3 mb-0 text-white">{{ strtoupper(substr($patient->nom, 0, 1)) }}</span>
                        </div>
                        <div class="ms-3">
                            <h4 class="text-white mb-0 fw-bold">{{ $patient->nom }} {{ $patient->prenom }}</h4>
                            <div class="d-flex gap-1 mt-1">
                                <span class="badge bg-danger">Groupe : {{ $patient->groupe_sanguin }}</span>
                                @if(auth()->user()->hasRole('admin') || auth()->user()->name === 'Administrateur')
                                    <span class="badge bg-secondary">Service : {{ $patient->service->name ?? 'Aucun' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="info-row mb-3">
                        <small class="text-info text-uppercase fw-bold d-block">Téléphone</small>
                        <span class="text-white h5">{{ $patient->telephone }}</span>
                    </div>

                    <div class="info-row mb-3">
                        <small class="text-info text-uppercase fw-bold d-block">Âge / Sexe</small>
                        <span class="text-white h5">{{ $patient->age }} ans — {{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</span>
                    </div>

                    <div class="info-row border-0">
                        <small class="text-info text-uppercase fw-bold d-block">Adresse</small>
                        <span class="text-white-50 small">{{ $patient->adresse ?? 'Non renseignée' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row g-4 h-100">
                <div class="col-12 h-50">
                    <div class="card card-dashboard-style">
                        <div class="card-header border-0 bg-header-dark">
                            <h5 class="mb-0 text-warning fw-bold small text-uppercase">
                                <i class="bi bi-clipboard2-pulse me-2"></i>Antécédents & Allergies</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 border-end border-white-10">
                                    <p class="text-white-50 mb-1 small">Historique médical :</p>
                                    <p class="text-white fw-bold">{{ $patient->antecedents ?? 'Aucun antécédent' }}</p>
                                </div>
                                <div class="col-md-6 px-4">
                                    <p class="text-white-50 mb-1 small text-warning">Allergies connues :</p>
                                    <p class="text-warning fw-bold">{{ $patient->allergies ?? 'Aucune allergie' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 h-50">
                    <div class="card card-dashboard-style">
                        <div class="card-header border-0 bg-header-dark d-flex justify-content-between">
                            <h5 class="mb-0 text-info fw-bold small text-uppercase">
                                <i class="bi bi-calendar-check me-2"></i>Consultations</h5>
                            @if(auth()->user()->name === 'Administrateur' || auth()->user()->hasAnyRole(['admin', 'administrateur']) || auth()->user()->hasRole('medecin') || auth()->user()->role === 'medecin' || auth()->user()->role === 'admin')
                                <a href="{{ route('consultations.create', $patient->id) }}"
                                class="btn btn-sm btn-info text-white text-decoration-none small fw-bold">+ Nouvelle</a>
                            @endif
                        </div>
                        <div class="card-body p-0 overflow-auto" style="max-height: 200px;">
                            <table class="table table-custom mb-0 color-white">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Diagnostic</th>
                                        <th>Signes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($consultations as $c)
                                    <tr>
                                        <td class="text-dark fw-bold">{{ \Carbon\Carbon::parse($c->date_consultation)->format('d/m/Y') }}</td>
                                        <td class="text-dark small">{{ Str::limit($c->diagnostic, 40) }}</td>
                                        <td class="text-dark small">{{ $c->tension ?? '-' }}__
                                            {{ $c->poids ?? '-' }}kg</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-4">
            <div class="card card-dashboard-style">
                <div class="card-header border-0 bg-header-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary fw-bold small text-uppercase">
                        <i class="bi bi-capsule me-2"></i>Historique Ordonnances</h5>
                    @if(auth()->user()->name === 'Administrateur' || auth()->user()->hasAnyRole(['admin', 'administrateur']) || auth()->user()->hasRole('medecin') || auth()->user()->role === 'medecin' || auth()->user()->role === 'admin')
                        <a href="{{ route('ordonnances.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary btn-sm rounded-pill text-white shadow-sm px-3">+ Nouvelle</a>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-custom mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Date d'émission</th>
                                <th class="text-end px-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patient->ordonnances as $o)
                            <tr>
                                <td class="text-darkfw-bold">ORD-#{{ $o->id }}</td>
                                <td class="text-dark-50">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end px-4">
                                    <div class="btn-group gap-1">
                                        <a href="{{ route('ordonnances.pdf', $o->id) }}" class="btn btn-sm btn-danger rounded"><i class="bi bi-file-pdf"></i></a>
                                        @if(auth()->id() == $o->user_id)
                                            <form action="{{ route('ordonnances.destroy', $o->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ?');">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-dark opacity-75"><i class="bi bi-trash"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
