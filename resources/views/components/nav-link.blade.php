@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-emerald-700 text-white'
            : 'text-gray-300 hover:bg-emerald-700 hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
