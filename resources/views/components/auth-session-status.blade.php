@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-danger text-sm text-center']) }}>
        {{ $status }}
    </div>
@endif
