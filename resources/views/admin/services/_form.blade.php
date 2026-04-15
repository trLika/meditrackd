{{-- resources/views/admin/services/_form.blade.php --}}

@csrf

<div class="mb-3">
    <label for="name" class="form-label">Nom du Service</label>
    <input
        type="text"
        name="name"
        id="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $service->name ?? '') }}"
        required
    >
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea
        name="description"
        id="description"
        class="form-control @error('description') is-invalid @enderror"
        rows="4"
    >{{ old('description', $service->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
