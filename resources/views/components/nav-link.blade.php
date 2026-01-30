@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 bg-white dark:bg-white border border-transparent rounded-full font-semibold text-xs text-red-600 uppercase tracking-widest hover:bg-yellow-50 focus:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition ease-in-out duration-150 shadow-lg transform scale-105'
            : 'inline-flex items-center px-4 py-2 bg-transparent border border-transparent rounded-full font-medium text-xs text-white uppercase tracking-widest hover:text-yellow-200 hover:bg-white/10 focus:outline-none transition ease-in-out duration-150 hover:scale-105';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
