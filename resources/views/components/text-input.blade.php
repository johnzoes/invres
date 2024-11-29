@props(['disabled' => false])

<input 
    {{ $disabled ? 'disabled' : '' }} 
    {!! $attributes->merge([
        'class' => 'border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 dark:text-gray-300 
                    focus:border-gray-500 dark:focus:border-gray-700 
                    focus:ring focus:ring-gray-200 dark:focus:ring-blue-600 
                 
                    rounded-lg shadow-sm py-3 px-3 text-sm'
    ]) !!}
>
