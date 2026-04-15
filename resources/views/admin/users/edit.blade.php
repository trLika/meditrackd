@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Assigner des services à {{ $user->name }}</h2>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf @method('PUT')
        <select name="services[]" class="form-select" multiple>
            @foreach($services as $service)
                <option value="{{ $service->id }}" {{ $user->services->contains($service->id) ? 'selected' : '' }}>
                    {{ $service->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-success mt-3">Enregistrer</button>
    </form>
</div>
@endsection
