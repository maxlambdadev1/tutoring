@props(['name', 'label', 'items', 'selectedItems' => null])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}" multiple  class="form-select @error($name) is-invalid @enderror" style="height: 129px;" {{ $attributes->wire('model') }}  {{ $attributes }}>
        @forelse($items as $item)
            <option value="{{ $item->id }}" @if(!empty($selectedItems) && in_array($item->id, $selectedItems)) selected @endif>{{ $item->name }}</option>
        @empty
            <option value="" >There are no options</option>
        @endforelse
    </select>
    @error($name)
    <div class=" invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>