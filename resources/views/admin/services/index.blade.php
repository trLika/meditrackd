@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Liste des Services Hospitaliers</h2>
        {{-- Correction : ajout de admin. --}}
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Ajouter un service
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="table table-hover table-bordered shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Médecins Assignés</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $service)
            <tr>
                <td class="align-middle">{{ $service->name }}</td>
                <td class="align-middle">{{ $service->description ?? 'Aucune description' }}</td>
                <td class="align-middle">
                    @forelse($service->users as $medecin)
                        <span class="badge bg-success me-1">{{ $medecin->name }}</span>
                    @empty
                        <span class="text-muted">Aucun médecin assigné</span>
                    @endforelse
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $service->id }}">
                        <i class="bi bi-person-plus"></i> Assigner
                    </button>
                    <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-4">
                    Aucun service enregistré pour le moment.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Modals d'assignation pour chaque service --}}
    @foreach($services as $service)
    <div class="modal fade" id="assignModal{{ $service->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assigner un médecin au service {{ $service->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.services.assign-medecin', $service->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="medecin_id" class="form-label">Médecin</label>
                            <select name="medecin_id" id="medecin_id" class="form-select" required>
                                <option value="">Choisir un médecin</option>
                                @foreach(\App\Models\User::role('medecin')->get() as $medecin)
                                <option value="{{ $medecin->id }}">{{ $medecin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Assigner
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
