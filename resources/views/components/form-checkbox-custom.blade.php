@props(['name', 'label', 'value' => ''])

<div class="mb-3">
    <h5 class="form-label">{{ $label }}</h5>
    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="{{$value}}" data-switch="success"  {{ $attributes->wire('model') }}  {{ $attributes }}/>
    <label for="{{ $name }}" data-on-label="Yes" data-off-label="No"></label>
</div>