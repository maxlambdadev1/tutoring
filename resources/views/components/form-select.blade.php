@props(['name', 'label', 'items', 'selectedValue' => null])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}"  class="form-select @error($name) is-invalid @enderror" {{ $attributes->wire('model') }}  {{ $attributes }}>
        <option value="">Please select...</option>
        @foreach($items as $item)
            <option value="{{ $item->id }}" @if(!empty($selectedValue) && $item->id == $selectedValue) selected @endif>{{ $item->name }}</option>
        @endforeach
    </select>
    @error($name)
    <div class=" invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>