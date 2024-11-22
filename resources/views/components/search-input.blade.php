<div class="relative flex items-center">
    <!-- Ícono de Lupa -->
    <div class="absolute left-3">
        <x-icon name="lupa" class="text-blue-500 w-5 h-5"></x-icon> <!-- Asegúrate de ajustar el tamaño si es necesario -->
    </div>
    <!-- Input de Búsqueda -->
    <x-text-input 
        type="text"
        name="{{ $name ?? 'search' }}"
        id="{{ $id ?? 'search' }}"
        placeholder="{{ $placeholder ?? 'Buscar...' }}"
        class="pl-12 w-full {{ $class ?? '' }}"
        value="{{ $value ?? '' }}"
 
    />
</div>
