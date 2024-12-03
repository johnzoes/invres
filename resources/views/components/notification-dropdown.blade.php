{{-- components/notification-dropdown.blade.php --}}
@props(['isMobile' => false])

@if($isMobile)
    {{-- Vista Móvil --}}
    <div class="bg-gray-900 rounded-t-2xl shadow-xl max-h-[80vh] overflow-y-auto">
        <div class="sticky top-0 z-10 flex items-center justify-between p-4 border-b border-gray-700/30 backdrop-blur bg-gray-900/95">
            <h2 class="text-lg font-medium text-white">Notificaciones</h2>
            <button @click="showNotifications = false" 
                    class="text-gray-400 hover:text-white p-2 rounded-lg hover:bg-gray-800/50 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @include('components.notification-content')
    </div>
@else
    {{-- Vista Desktop --}}
    <div x-show="showNotifications"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="showNotifications = false"
         class="fixed top-0 right-0 w-96 h-full bg-gray-900 border-l border-gray-700/30 shadow-xl overflow-hidden z-50">
        
        <div class="sticky top-0 z-10 backdrop-blur bg-gray-900/95">
            <div class="flex items-center justify-between p-6 border-b border-gray-700/30">
                <div>
                    <h2 class="text-lg font-semibold text-white">Notificaciones</h2>
                    <p class="text-sm text-gray-400 mt-1">
                        {{ $notificaciones->count() }} nuevas
                    </p>
                </div>
                <button @click="showNotifications = false" 
                        class="p-2 rounded-lg hover:bg-gray-800/50 transition-all duration-200">
                    <svg class="w-5 h-5 text-gray-400 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="h-[calc(100vh-80px)] overflow-y-auto">
            @include('components.notification-content')
        </div>
    </div>
@endif