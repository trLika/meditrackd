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

    <table class="table table-hover table-bordered shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $service)
            <tr>
                <td class="align-middle">{{ $service->name }}</td>
                <td class="align-middle">{{ $service->description ?? 'Aucune description' }}</td>
                <td class="text-center align-middle">
                    {{-- Correction : ajout de admin. --}}
                    <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                    {{-- Correction : ajout de admin. --}}
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
                <td colspan="3" class="text-center text-muted py-4">
                    Aucun service enregistré pour le moment.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
