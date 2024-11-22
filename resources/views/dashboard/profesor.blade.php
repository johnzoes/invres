<x-app-layout>

  

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Botón de acción dentro de un contenedor flex -->
                <div class="flex justify-end">
            <a href="{{ route('reservas.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-20 px-6 rounded-lg w-full text-center"
               style="background-image: url('{{ asset('images/bg-new-rv.jpg') }}'); background-size: cover; background-position: center;">
                Nueva Reserva
            </a>
        </div>
            <!-- Grid de Reservas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Reservas Pendientes -->
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Reservas Pendientes</h3>
                    <div class="max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-300">
                        @forelse($reservasPendientes as $reserva)
                            <a href="{{ route('reservas.show', $reserva->id) }}" class="block bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg p-4 mb-2 text-white hover:bg-gray-700">
                                <h1 class="text-sm">ID: {{ $reserva->id }}</h1> 
                                <h2 class="text-sm">Fecha: {{ $reserva->created_at->format('d-m-Y H:i') }}</h2> 
                                <p class="text-sm">Curso: {{ $reserva->unidadDidactica->nombre }}</p>
                            </a>
                        @empty
                            <p class="text-white">No tienes reservas pendientes.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Reservas Aprobadas -->
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Reservas Aprobadas</h3>
                    <div class="max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-300">
                        @forelse($reservasAprobadas as $reserva)
                            <a href="{{ route('reservas.show', $reserva->id) }}" class="block bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg p-4 mb-2 text-white hover:bg-gray-700">
                                <h1 class="text-sm">ID: {{ $reserva->id }}</h1> 
                                <p class="text-sm">Curso: {{ $reserva->unidadDidactica->nombre }}</p>
                            </a>
                        @empty
                            <p class="text-white">No tienes reservas aprobadas.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Reservas Prestadas -->
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Reservas Prestadas</h3>
                    <div class="max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-300">
                        @forelse($reservasPrestadas as $reserva)
                            <a href="{{ route('reservas.show', $reserva->id)  }}" class="block bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg p-4 mb-2 text-white hover:bg-gray-700">
                                <h1 class="text-sm">ID: {{ $reserva->id }}</h1> 
                                <p class="text-sm">Curso: {{ $reserva->unidadDidactica->nombre }}</p>
                            </a>
                        @empty
                            <p class="text-white">No tienes reservas prestadas.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Reservas Devueltas -->
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Reservas Devueltas</h3>
                    <div class="max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-300">
                        @forelse($reservasDevueltas as $reserva)
                            <a href="{{ route('reservas.show', $reserva->id) }}" class="block bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg p-4 mb-2 text-white hover:bg-gray-700">
                                <h1 class="text-sm">ID: {{ $reserva->id }}</h1> 
                                <p class="text-sm">Curso: {{ $reserva->unidadDidactica->nombre }}</p>
                            </a>
                        @empty
                            <p class="text-white">No tienes reservas devueltas.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
