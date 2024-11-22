<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Historial de Estados') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-8">
            <h1 class="text-3xl font-bold mb-8 text-center">Historial de Estados</h1>

            <!-- Timeline container -->
            <div class="relative border-l-4 border-gray-400">
                <!-- Solicitado -->
                <div class="mb-12 relative">
                    <div class="absolute w-10 h-10 bg-green-500 rounded-full -left-5 border-2 border-white"></div>
                    <h3 class="text-xl ml-12 font-semibold text-gray-700">
                        Solicitado
                    </h3>
                    <p class="text-base ml-12 text-gray-500">
                        {{ \Carbon\Carbon::parse($fechaPrestamo)->translatedFormat('M d \d\e Y') }}
                    </p>
                    <p class="text-base ml-12 text-gray-500">
                        {{ \Carbon\Carbon::parse($fechaPrestamo)->format('h:i a') }}
                    </p>
                </div>

                <!-- Estados del historial -->
                @foreach ($historial as $evento)
                    <div class="mb-12 relative">
                        <div class="absolute w-10 h-10 bg-blue-500 rounded-full -left-5 border-2 border-white"></div>
                        <h3 class="text-xl ml-12 font-semibold text-gray-700">
                            {{ ucfirst($evento->estado) }}
                        </h3>
                        <p class="text-base ml-12 text-gray-500">
                            {{ \Carbon\Carbon::parse($evento->fecha_estado)->translatedFormat('M d \d\e Y') }}
                        </p>
                        <p class="text-base ml-12 text-gray-500">
                            {{ \Carbon\Carbon::parse($evento->fecha_estado)->format('h:i a') }}
                        </p>

                        @if ($evento->estado === 'rechazado')
                            <p class="text-base ml-12 text-red-600 font-bold">Mensaje: {{ $evento->motivo_rechazo }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('reservas.index') }}" class="text-blue-500 hover:underline text-lg">
                    Volver a la lista de reservas
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
