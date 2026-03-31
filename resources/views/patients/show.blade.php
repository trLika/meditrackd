@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Les informations de votre patient : {{ $patient->nom }} {{ $patient->prenom }}</h5>
        </div>
<!--bloc d'insertion des donnees du patients sur la fiche -->
@if(auth()->user()->role === 'stagiaire')
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Mode lecture seule : Vous n'avez pas les droits pour modifier ce dossier.
    </div>
@endif
@if($patient->is_critique)
        <div class="alert alert-danger mb-0">
                <i class="bi bi-exclamation-triangle-fill"></i> <strong>CAS CRITIQUE :</strong>
                Une attention particulière est requise pour ce patient.
            </div>
        @endif
     <div class="card-body">
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>ID :</strong> #{{ $patient->id }}</li>
        <li class="list-group-item"><strong>Téléphone :</strong> {{ $patient->telephone }}</li>
        <li class="list-group-item"><strong>Groupe Sanguin :</strong> {{ $patient->groupe_sanguin }}</li>
        <li class="list-group-item"><strong>Sexe :</strong> {{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</li>
       <li class="list-group-item"><strong>Âge :</strong> {{ $patient->age }} ans</li>

</li>



        <li class="list-group-item bg-light">
            <strong><i class="bi bi-clipboard2-pulse text-danger"></i> Antécédents Médicaux :</strong>
            <p class="mt-1 mb-0 text-muted">{{ $patient->antecedents ?? 'Aucun antécédent' }}</p>
        </li>




</div>
               <li class="list-group-item">
    <i class="bi bi-geo-alt-fill text-danger me-2"></i>
    <strong>Adresse :</strong>
    {{ $patient->adresse ?? 'Non renseignée' }}
</li>
                <li class="list-group-item"><strong>Date d'inscription :</strong> {{ $patient->created_at->format('d/m/Y') }}</li>
<li class="list-group-item">

        </li>

        @if($patient->is_critique)
        <li class="list-group-item bg-light-danger">

        </li>
        @endif
    </ul>




            <div class="mt-4">
                <a href="{{ route('patients.index') }}" class="btn btn-danger">Retour à la liste</a>
  @if(auth()->user()->role !== 'stagiaire')<!--fonction pour empecher le stagiaire de faire des modification-->
                <a href="{{ route('patients.edit', $patient->id) }}"
                 class="btn btn-primary text-white">Modifier ce dossier</a>
                 @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header bg-dark text-primary d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-capsule"></i> Historique des Ordonnances</h5>
        <a href="{{ route('ordonnances.create', $patient->id) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Nouvelle Ordonnance
        </a>
    </div>
    <div class="card-body">
        @if($patient->ordonnances->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->ordonnances as $ordonnance)
                        <tr>
                            <td>{{ $ordonnance->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('ordonnances.pdf', $ordonnance->id) }}" class="btn btn-sm btn-danger">PDF</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-muted">Aucune ordonnance enregistrée.</p>
        @endif
    </div>
</div>



<div class="card shadow border-0 mt-4">
        <div class="card-header bg-dark text-info">
            <h5 class="mb-0"><i class="bi bi-journal-medical"></i> Historique des Consultations</h5>
<a href="{{ route('consultations.create', $patient->id) }}" class="btn btn-sm btn-info text-white float-end">
    <i class="bi bi-plus-circle "></i> Nouvelle Consultation
</a>
  </div>
        <div class="card-body">
            @if($consultations->isEmpty())
                <p class="text-muted text-center my-3">Aucune consultation enregistrée pour ce patient.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Diagnostic</th>
                                <th>Traitement</th>
                                <th>Signes vitaux</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consultations as $consultation)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}</td>
                                <td>{{ $consultation->diagnostic }}</td>
                                <td>{{ $consultation->traitement }}</td>
                                <td>

                                    <small>
                                        Tension : {{ $consultation->tension ?? '-' }}<br>
                                        Poids : {{ $consultation->poids ? $consultation->poids.'kg' : '-' }}
                                    </small>
                                </td>

                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>


@endsection
