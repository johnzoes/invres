<table class="w-full table-auto text-left bg-white dark:bg-gray-800 shadow-sm rounded-lg">
    <thead>
        <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-sm leading-normal">
            <th class="py-3 px-6 text-left rounded-l-lg">ID</th>
            <th class="py-3 px-6 text-left">Datos</th>
            <th class="py-3 px-6 text-left">Usuario</th>
            <th class="py-3 px-6 text-left">Correo</th>
            <th class="py-3 px-6 text-left">Rol</th>
            <th class="py-3 px-6 text-left rounded-r-lg">Acciones</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 dark:text-gray-100 text-sm font-light">
        @forelse($usuarios as $usuario)
            <tr 
                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transform hover:scale-105 transition-transform cursor-pointer" 
                onclick="window.location.href='{{ route('usuarios.show', $usuario->id) }}';">
                <td class="py-3 px-6 text-left rounded-l-lg">{{ $usuario->id }}</td>
                <td class="py-3 px-6 text-left font-bold">
                    {{ $usuario->nombre . " " . $usuario->apellidos }}
                </td>
                <td class="py-3 px-6 text-left">{{ $usuario->nombre_usuario }}</td>
                <td class="py-3 px-6 text-left">{{ $usuario->email }}</td>
                <td class="py-3 px-6 text-left">{{ implode(', ', $usuario->roles->pluck('name')->toArray()) }}</td>
                <td class="py-3 px-6 flex justify-start space-x-4 rounded-r-lg">
                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="text-blue-500 hover:text-blue-700">
                        <x-icon name="edit"></x-icon>
                    </a>
                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            <x-icon name="delete"></x-icon>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="py-4 px-6 text-center text-gray-500">
                    No se encontraron resultados
                </td>
            </tr>
        @endforelse
    </tbody>
</table>