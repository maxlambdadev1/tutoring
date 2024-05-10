@props(['name', 'label', 'value' => ''])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <div class="input-group input-group-merge @error($name) is-invalid @enderror">
        <input type="password" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" {{ $attributes->wire('model') }}  {{ $attributes }}>
        <div class="input-group-text" data-password="false">
            <span class="password-eye"></span>
        </div>
    </div>
    @error($name)
    <div class=" invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>