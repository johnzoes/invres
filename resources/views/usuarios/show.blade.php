<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles del Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-xl font-bold mb-4">Detalles del Usuario</h1>

                    <p><strong>ID:</strong> {{ $usuario->id }}</p>
                    <p><strong>Nombre:</strong> {{ $usuario->nombre }}</p>
                    <p><strong>Apellidos:</strong> {{ $usuario->apellidos }}</p>
                    <p><strong>Nombre de Usuario:</strong> {{ $usuario->nombre_usuario }}</p>
                    <p><strong>Email:</strong> {{ $usuario->email }}</p>
                    <p><strong>Rol:</strong> {{ $usuario->roles->pluck('name')->first() }}</p>

                    @if($usuario->roles->pluck('name')->first() === 'asistente' && $usuario->asistente)
                        <p><strong>Salones:</strong></p>
                        <ul>
                            @foreach($usuario->asistente->salones as $salon)
                                <li>- {{ $salon->nombre_salon }}</li>
                            @endforeach
                        </ul>
                        <p><strong>Turno:</strong> {{ ucfirst($usuario->asistente->turno) }}</p>
                    @endif

                    @if($usuario->roles->pluck('name')->first() === 'profesor' && $usuario->profesor)
                        <p><strong>Reservas realizadas:</strong> {{ $usuario->profesor->reservas->count() }}</p>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('usuarios.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">
                        Volver a la Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
