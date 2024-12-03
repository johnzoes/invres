{{-- Componente search-input.blade.php --}}
<div class="relative mb-6">
    <input 
        type="text" 
        id="searchInput"
        class="w-full bg-gray-700/50 border border-gray-600/50 text-gray-200 text-sm rounded-xl px-4 py-2.5 pl-10 focus:border-green-500/50 focus:ring-1 focus:ring-green-500/50 focus:outline-none transition-colors duration-200"
        placeholder="Buscar..."
    >
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
        </svg>
    </div>
</div>