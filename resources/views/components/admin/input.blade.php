@props(['name', 'label', 'value' => null, 'type' => 'text', 'required' => false, 'help' => null])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-semibold">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $value) }}"
           @required($required)
           {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}>
    @if ($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
