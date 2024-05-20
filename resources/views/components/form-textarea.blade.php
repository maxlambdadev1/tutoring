@props(['name', 'label', 'value' => ''])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <textarea class="form-control @error($name) is-invalid @enderror" name="{{ $name }}" id="{{ $name }}" rows="5" {{ $attributes->wire('model') }} {{ $attributes }}>{{ old($name, $value) }}</textarea>
    @error($name)
    <div class=" invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>