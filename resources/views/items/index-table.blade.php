<table class="w-full text-left text-sm bg-gray-900 rounded-lg overflow-hidden">
    <thead class="bg-gray-700 text-gray-200">
        <tr>
            <th class="py-3 px-6">ID</th>
            <th class="py-3 px-6">Descripción</th>
            <th class="py-3 px-6">Cantidad</th>
            <th class="py-3 px-6">Tipo</th>
            <th class="py-3 px-6">Marca</th>
            <th class="py-3 px-6">Estado</th>
            <th class="py-3 px-6">Acciones</th>
        </tr>
    </thead>
    <tbody class="text-gray-100">
        @forelse($items as $item)
            <tr class="border-b border-gray-700 hover:bg-gray-700 transition hover:shadow-lg cursor-pointer transform hover:-translate-y-1"
                onclick="window.location='{{ route('items.show', $item->id) }}'">
                <td class="py-3 px-6">{{ $item->id }}</td>
                <td class="py-3 px-6">{{ $item->descripcion }}</td>
                <td class="py-3 px-6">{{ $item->cantidad }}</td>
                <td class="py-3 px-6">{{ $item->tipo }}</td>
                <td class="py-3 px-6">{{ $item->marca }}</td>
                <td class="py-3 px-6">
                    @php
                        $estadoColor = match($item->estado) {
                            'disponible' => 'bg-green-600',
                            'ocupado' => 'bg-red-600',
                            default => 'bg-yellow-500'
                        };
                    @endphp
                    <span class="{{ $estadoColor }} text-white font-bold px-3 py-1 rounded-lg text-xs uppercase">
                        {{ $item->estado }}
                    </span>
                </td>
                <td class="py-3 px-6">
                    <a href="{{ route('items.show', $item->id) }}" class="text-blue-400 hover:text-blue-600">
                        <x-icon name="view"></x-icon>
                    </a>
                    <a href="{{ route('items.edit', $item->id) }}" class="text-yellow-400 hover:text-yellow-600">
                        <x-icon name="edit"></x-icon>
                    </a>
                    <a href="#" class="text-red-400 hover:text-red-600" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $item->id }}').submit();">
                        <x-icon name="delete"></x-icon>
                    </a>
                    <form id="delete-form-{{ $item->id }}" action="{{ route('items.destroy', $item->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-gray-400 py-4">No se encontraron ítems</td>
            </tr>
        @endforelse
    </tbody>
</table>
