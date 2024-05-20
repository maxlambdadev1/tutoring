@props(['name', 'label', 'items', 'firstOptionLabel' => 'Please select...', 'existFirstOption' => true, 'selectedValue' => null])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}"  class="form-select @error($name) is-invalid @enderror" {{ $attributes->wire('model') }}  {{ $attributes }}>
        @if ($existFirstOption) <option value="">{{ $firstOptionLabel }}</option> @endif
        @foreach($items as $item)
            <option value="{{ $item }}" @if(!empty($selectedValue) && $item == $selectedValue) selected @endif>{{ $item }}</option>
        @endforeach
    </select>
    @error($name)
    <div class=" invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>