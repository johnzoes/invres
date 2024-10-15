<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

            <a href="{{ route('usuarios.create') }}" class="text-yellow-500 p-6">crear</a>


                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2>Lista de Usuarios</h2>
                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700">
                                <th>ID</th>
                                <th>Nombre de Usuario</th>
                                <th>Correo Electrónico</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->id }}</td>
                                <td>{{ $usuario->nombre_usuario }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ implode(', ', $usuario->roles->pluck('name')->toArray()) }}</td>
                                <td class="flex space-x-2">
                                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="text-yellow-500">Editar</a>
                                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
