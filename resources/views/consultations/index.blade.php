@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-gradient-info text-dark p-3 rounded-top-4">
            <h4 class="mb-0">
                <i class="bi bi-clipboard2-pulse me-2 text-info"></i>
                Liste des Consultations
            </h4>
        </div>

        <div class="card-body p-4">
            @if($consultations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Patient</th>
                                <th>Diagnostic</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consultations as $consultation)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('patients.show', $consultation->patient->id) }}" class="text-decoration-none">
                                            {{ $consultation->patient->nom }} {{ $consultation->patient->prenom }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ Str::limit($consultation->diagnostic, 50) }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('consultations.pdf', $consultation->id) }}" 
                                               class="btn btn-sm btn-outline-danger" title="Générer PDF">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-clipboard2-pulse display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Aucune consultation enregistrée</h5>
                    <p class="text-muted">Commencez par ajouter une consultation pour un patient.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection