@props(['name', 'label', 'value' => null, 'required' => false, 'rows' => 4])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-semibold">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" @required($required)
              {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}>{{ old($name, $value) }}</textarea>
    <div class="form-text">Le HTML simple est accepté (&lt;p&gt;, &lt;b&gt;, &lt;i&gt;, &lt;ul&gt;…).</div>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
