@props(['name', 'label', 'type' => 'text', 'value' => ''])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input type="{{ $type }}" class="form-control @error($name) is-invalid @enderror" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}" {{ $attributes->wire('model') }} {{ $attributes }}>
    @error($name)
    <div class=" invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>