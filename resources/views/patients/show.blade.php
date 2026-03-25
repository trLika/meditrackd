@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Les informations de votre patient : {{ $patient->nom }} {{ $patient->prenom }}</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>ID :</strong> #{{ $patient->id }}</li>
                <li class="list-group-item"><strong>Téléphone :</strong> {{ $patient->telephone }}</li>
                <li class="list-group-item"><strong>Groupe Sanguin :</strong> {{ $patient->groupe_sanguin }}</li>
                <li class="list-group-item">
    <strong>Sexe :</strong>
    @if($patient->sexe == 'M')
        Masculin
    @elseif($patient->sexe == 'F')
        Féminin
    @else
        <span class="text-muted">Non renseigné</span>
    @endif
</li>
                <li class="list-group-item">
    <i class="bi bi-geo-alt-fill text-danger me-2"></i>
    <strong>Adresse :</strong>
    {{ $patient->adresse ?? 'Non renseignée' }}
</li>
                <li class="list-group-item"><strong>Date d'inscription :</strong> {{ $patient->created_at->format('d/m/Y') }}</li>
            </ul>
            <div class="mt-4">
                <a href="{{ route('patients.index') }}" class="btn btn-danger">Retour à la liste</a>
                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary text-white">Modifier ce dossier</a>
            </div>
        </div>
    </div>
</div>
<div class="card shadow border-0 mt-4">
        <div class="card-header bg-dark text-danger">
            <h5 class="mb-0"><i class="bi bi-journal-medical"></i> Historique des Consultations</h5>
<a href="{{ route('consultations.create', $patient->id) }}" class="btn btn-sm btn-info text-white float-end">
    <i class="bi bi-plus-circle"></i> Nouvelle Consultation
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
<a href="{{ route('consultations.pdf', $consultation->id) }}" class="btn btn-sm btn-danger">
    <i class="bi bi-file-earmark-pdf"></i> PDF
</a>
      
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
    ```


@endsection
