<x-app-layout>
    <div class="container mx-auto px-2 sm:px-4 py-6 bg-gray-800 rounded-lg">
        <h1 class="text-xl font-bold text-white mb-4 ml-4 sm:ml-8">Historial de Reservas</h1>

        @php
            $mesActual = null;
            $mesActualNombre = now()->translatedFormat('F Y');
        @endphp

        <div class="rounded-lg p-2 sm:p-6 shadow-lg z-10">
            <!-- Vista móvil: Cards -->
            <div class="block lg:hidden">
                @foreach ($reservas as $reserva)
                    @php
                        $mesReserva = $reserva->created_at->translatedFormat('F Y');
                        $mesIdentificador = str_replace(' ', '-', strtolower($mesReserva));
                        
                        if ($mesActual !== $mesReserva) {
                            echo '<div class="mb-4 mt-6 flex items-center justify-center">
                                    <hr class="flex-grow border-gray-600">
                                    <span class="mx-4 font-semibold text-white">'.$mesReserva.'</span>
                                    <button onclick="toggleMonth(\'mobile-'.$mesIdentificador.'\')" class="focus:outline-none">
                                        <svg id="icon-mobile-'.$mesIdentificador.'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform text-white" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.11l3.7-3.88a.75.75 0 111.08 1.04l-4 4.2a.75.75 0 01-1.08 0l-4-4.2a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>';
                            $mesActual = $mesReserva;
                        }
                    @endphp
                    
                    <div class="mobile-fila-{{ $mesIdentificador }} bg-gray-900 rounded-lg mb-4 p-4 shadow hover:shadow-lg transition duration-200 border-l-4 border-r-4 border-gray-600"
                         onclick="window.location='{{ route('reservas.show', $reserva->id) }}'">
                        <div class="space-y-3 text-white">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">ID:</span>
                                <span>{{ $reserva->id }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Profesor:</span>
                                <span class="font-bold">{{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Unidad:</span>
                                <span>{{ $reserva->unidadDidactica->nombre }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Estado:</span>
                                @if ($reserva->detalles->isNotEmpty())
                                    @php
                                        $estado = ucfirst($reserva->detalles->first()->estado);
                                        $colorEstado = match($estado) {
                                            'Aprobado' => 'bg-green-600',
                                            'Rechazado' => 'bg-red-600',
                                            'Pendiente' => 'bg-yellow-600',
                                            'Prestado' => 'bg-blue-600',
                                            'Devuelto' => 'bg-purple-600',
                                            default => 'bg-gray-500'
                                        };
                                    @endphp
                                    <span class="{{ $colorEstado }} text-white font-bold px-3 py-1 rounded-lg text-sm">
                                        {{ $estado }}
                                    </span>
                                @else
                                    <span class="bg-gray-500 text-white font-bold px-3 py-1 rounded-lg text-sm">
                                        No definido
                                    </span>
                                @endif
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Fecha:</span>
                                <span>{{ $reserva->created_at->translatedFormat('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Vista desktop: Tabla -->
            <div class="hidden lg:block">
                <table class="w-full text-left text-sm rounded-lg overflow-hidden border-l-4 border-r-4 border-gray-600">
                    <thead class="border border-gray-400 border-opacity-50 rounded-lg">
                        <tr class="text-white">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Profesor</th>
                            <th class="px-4 py-2">Unidad Didáctica</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2 border-gray-500 border-opacity-30">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $mesActual = null; @endphp
                        @foreach ($reservas as $reserva)
                            @php
                                $mesReserva = $reserva->created_at->translatedFormat('F Y');
                                $mesIdentificador = str_replace(' ', '-', strtolower($mesReserva));
                            @endphp

                            @if ($mesActual !== $mesReserva)
                                @if ($mesActual)
                                    <tr><td colspan="5" class="py-2"></td></tr>
                                @endif
                                <tr class="text-white">
                                    <td colspan="5" class="py-2 border-t border-gray-600">
                                        <div class="flex items-center justify-center">
                                            <hr class="flex-grow border-gray-600">
                                            <span class="mx-4 font-semibold">{{ $mesReserva }}</span>
                                            <button onclick="toggleMonth('{{ $mesIdentificador }}')" class="focus:outline-none ml-2">
                                                <svg id="icon-{{ $mesIdentificador }}" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.11l3.7-3.88a.75.75 0 111.08 1.04l-4 4.2a.75.75 0 01-1.08 0l-4-4.2a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @php $mesActual = $mesReserva; @endphp
                            @endif

                            <tr class="hover:bg-gray-800 hover:shadow-lg cursor-pointer transition duration-200 transform hover:-translate-y-1 border-l-4 border-r-4 border-gray-600 text-white fila-{{ $mesIdentificador }} relative z-[5]"
                                onclick="window.location='{{ route('reservas.show', $reserva->id) }}'">
                                <td class="px-4 py-2 border-b border-gray-600">{{ $reserva->id }}</td>
                                <td class="px-4 py-2 border-b border-gray-600 font-bold">
                                    {{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}
                                </td>
                                <td class="px-4 py-2 border-b border-gray-600">{{ $reserva->unidadDidactica->nombre }}</td>
                                <td class="px-4 py-2 border-b border-gray-600">
                                    @if ($reserva->detalles->isNotEmpty())
                                        @php
                                            $estado = ucfirst($reserva->detalles->first()->estado);
                                            $colorEstado = match($estado) {
                                                'Aprobado' => 'bg-green-600',
                                                'Rechazado' => 'bg-red-600',
                                                'Pendiente' => 'bg-yellow-600',
                                                'Prestado' => 'bg-blue-600',
                                                'Devuelto' => 'bg-purple-600',
                                                default => 'bg-gray-500'
                                            };
                                        @endphp
                                        <span class="{{ $colorEstado }} text-white font-bold px-3 py-1 rounded-lg">
                                            {{ $estado }}
                                        </span>
                                    @else
                                        <span class="bg-gray-500 text-white font-bold px-3 py-1 rounded-lg">No definido</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border-b border-gray-600 text-left">
                                    {{ $reserva->created_at->translatedFormat('M d, Y') }}<br>
                                    {{ $reserva->created_at->format('h:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            function toggleMonth(mesIdentificador) {
                if (mesIdentificador.startsWith('mobile-')) {
                    // Para vista móvil
                    const isMobile = mesIdentificador.startsWith('mobile-');
                    const baseIdentificador = isMobile ? mesIdentificador.substring(7) : mesIdentificador;
                    const rows = document.querySelectorAll(`${isMobile ? '.mobile-fila-' : '.fila-'}${baseIdentificador}`);
                    const icon = document.getElementById(`${isMobile ? 'icon-mobile-' : 'icon-'}${baseIdentificador}`);
                    
                    rows.forEach(row => row.classList.toggle('hidden'));
                    icon.classList.toggle('rotate-180');


                } else {
                    // Para vista desktop
                    const rows = document.querySelectorAll('.fila-' + mesIdentificador);
                    const icon = document.getElementById('icon-' + mesIdentificador);
                    rows.forEach(row => row.classList.toggle('hidden'));
                    icon.classList.toggle('rotate-180');
                }
            }
        </script>
    </div>
</x-app-layout>