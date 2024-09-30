<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/verificar-conexion', function () {
    try {
        // Intentar obtener la conexión PDO y el nombre de la base de datos
        DB::connection()->getPdo();
        return 'Conexión exitosa a la base de datos: ' . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return 'No se pudo conectar a la base de datos. Error: ' . $e->getMessage();
    }
});


// Rutas para gestionar reservas
Route::get('/reservas', [ReservaController::class, 'index']); // Listar todas las reservas
Route::get('/reservas/{id}', [ReservaController::class, 'show']); // Ver detalles de una reserva específica
Route::post('/reservas', [ReservaController::class, 'store']); // Crear una nueva reserva
Route::put('/reservas/{id}', [ReservaController::class, 'update']); // Actualizar una reserva
Route::delete('/reservas/{id}', [ReservaController::class, 'destroy']); // Eliminar una reserva