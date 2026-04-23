@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.medecins-services.index') }}">Médecins par Département</a></li>
                    <li class="breadcrumb-item active">Médecins non assignés</li>
                </ol>
            </nav>
            <h2>
                <i class="bi bi-person-exclamation"></i> 
                Médecins non assignés
            </h2>
        </div>
        <a href="{{ route('admin.medecins-services.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    @if($medecinsNonAssignes->count() > 0)
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            {{ $medecinsNonAssignes->count() }} médecin(s) ne sont pas assignés à un service.
        </div>

        <div class="row">
            @foreach($medecinsNonAssignes as $medecin)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $medecin->name }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Email:</strong> {{ $medecin->email }}</p>
                            <p class="mb-3"><strong>Rôle:</strong> Médecin</p>
                            
                            <form action="{{ route('admin.medecins-services.assign') }}" method="POST">
                                @csrf
                                <input type="hidden" name="medecin_id" value="{{ $medecin->id }}">
                                
                                <div class="mb-3">
                                    <label class="form-label">Assigner à un service:</label>
                                    <select name="service_id" class="form-select" required>
                                        <option value="">Sélectionner un service...</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-person-plus"></i> Assigner maintenant
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-success text-center">
            <i class="bi bi-check-circle"></i>
            <h4>Tous les médecins sont assignés !</h4>
            <p>Il n'y a aucun médecin sans service assigné.</p>
            <a href="{{ route('admin.medecins-services.index') }}" class="btn btn-primary">
                Voir la répartition complète
            </a>
        </div>
    @endif
</div>

@endsection
