<div>
    <table class="w-full text-left text-sm rounded-lg overflow-hidden border-l-4 border-r-4 border-gray-600">
        <thead class="border border-gray-400 border-opacity-50 rounded-lg">
            <tr class="text-white">
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Profesor</th>
                <th class="px-4 py-2">Unidad Didáctica</th>
                <th class="px-4 py-2">Estado</th>
                <th class="px-4 py-2">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservas as $reserva)
                @php
                    $estado = ucfirst($reserva->detalles->first()->estado ?? 'No definido');
                    $colorEstado = match($estado) {
                        'Aprobado' => 'bg-green-600',
                        'Rechazado' => 'bg-red-600',
                        'Pendiente' => 'bg-yellow-600',
                        'Prestado' => 'bg-blue-600',
                        'Devuelto' => 'bg-purple-600',
                        default => 'bg-gray-500'
                    };
                @endphp
                <tr class="hover:bg-gray-800 hover:shadow-lg cursor-pointer transition duration-200 transform hover:-translate-y-1 border-l-4 border-r-4 border-gray-600 text-white">
                    <td class="px-4 py-2 border-b border-gray-600">{{ $reserva->id }}</td>
                    <td class="px-4 py-2 border-b border-gray-600 font-bold">
                        {{ $reserva->profesor->usuario->nombre }} {{ $reserva->profesor->usuario->apellidos }}
                    </td>
                    <td class="px-4 py-2 border-b border-gray-600">{{ $reserva->unidadDidactica->nombre }}</td>
                    <td class="px-4 py-2 border-b border-gray-600">
                        <span class="{{ $colorEstado }} text-white font-bold px-3 py-1 rounded-lg">
                            {{ $estado }}
                        </span>
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
