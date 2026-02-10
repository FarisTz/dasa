@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => ' text-bold text-sm text-success']) }}>
        {{ $status }}
    </div>
@endif
