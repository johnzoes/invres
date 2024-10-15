<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Contenido principal -->
                    <h1>¡Estás dentro!</h1>

                    <!-- Referencias a las otras vistas importantes -->
                    <div class="mt-6">
                        <h3 class="font-semibold text-lg">Gestión del sistema</h3>
                        <ul class="list-disc pl-5">
                            <!-- Link a Usuarios -->
                            <li>
                                <a href="{{ route('usuarios.index') }}" class="text-blue-500 hover:text-blue-700">
                                    {{ __('Usuarios') }}
                                </a>
                            </li>

                            <!-- Link a Ítems -->
                            <li>
                                <a href="{{ route('items.index') }}" class="text-blue-500 hover:text-blue-700">
                                    {{ __('Ítems') }}
                                </a>
                            </li>

                            <!-- Link a Salones -->
                            <li>
                                <a href="{{ route('salones.index') }}" class="text-blue-500 hover:text-blue-700">
                                    {{ __('Salones') }}
                                </a>
                            </li>

                            <!-- Link a Armarios -->
                            <li>
                                <a href="{{ route('armarios.index') }}" class="text-blue-500 hover:text-blue-700">
                                    {{ __('Armarios') }}
                                </a>
                            </li>

                            <!-- Link a Reservas -->
                            <li>
                                <a href="{{ route('reservas.index') }}" class="text-blue-500 hover:text-blue-700">
                                    {{ __('Reservas') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contenido estático de prueba -->
                    <div class="mt-6">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
