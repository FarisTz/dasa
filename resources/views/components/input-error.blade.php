@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-danger']) }}>
        @foreach ((array) $messages as $message)
            <strong>{{ $message }}</strong>
        @endforeach
    </ul>
@endif
