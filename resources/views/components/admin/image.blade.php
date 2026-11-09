@props(['name', 'label', 'current' => null, 'url' => null])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-semibold">{{ $label }}</label>
    @if ($current)
        <div class="d-flex align-items-center gap-3 mb-2">
            <img src="{{ $url }}" alt="" class="admin-thumb">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="{{ $name }}_delete" value="1" id="{{ $name }}_delete">
                <label class="form-check-label" for="{{ $name }}_delete">Supprimer l'image actuelle</label>
            </div>
        </div>
    @endif
    <input type="file" id="{{ $name }}" name="{{ $name }}" accept="image/*"
           class="form-control @error($name) is-invalid @enderror">
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
