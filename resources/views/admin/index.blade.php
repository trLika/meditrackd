@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Gestion des Médecins</h2>

    <table class="table table-bordered table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Services Assignés</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            {{-- Utilisation de @forelse pour gérer le cas où il n'y a aucun médecin --}}
            @forelse($users as $user)
            <tr>
                <td class="align-middle">{{ $user->name }}</td>
                <td class="align-middle">{{ $user->email }}</td>
                <td class="align-middle">
                    {{-- Utilisation de forelse pour les services --}}
                    @forelse($user->services as $service)
                        <span class="badge bg-info text-dark me-1">{{ $service->name }}</span>
                    @empty
                        <span class="text-muted">Aucun service</span>
                    @endforelse
                </td>
                <td class="text-center align-middle">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-gear"></i> Gérer les accès
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Aucun médecin trouvé.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
