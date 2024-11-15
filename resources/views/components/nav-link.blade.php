@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-4 py-3 bg-white/5 text-sm font-medium text-gray-900 dark:text-gray-100 focus:outline-none rounded-md transition-all duration-300 ease-in-out'
    : 'inline-flex items-center px-4 py-3 border-l-4 border-transparent text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition-all duration-300 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
