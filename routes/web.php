<?php

use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\AsistenteController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\ArmarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UnidadDidacticaController;
use App\Http\Controllers\UsuarioController;

// Rutas para las reservas
Route::resource('reservas', ReservaController::class);

// Rutas para los ítems
Route::resource('items', ItemController::class);

// Rutas para las notificaciones
Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
Route::post('/notificaciones/{id}/read', [NotificacionController::class, 'markAsRead'])->name('notificaciones.read');

// Rutas para los asistentes
Route::resource('asistentes', AsistenteController::class);

// Rutas para los salones
Route::resource('salones', SalonController::class);

// Rutas para los armarios
Route::resource('armarios', ArmarioController::class);

// Rutas para las categorías
Route::resource('categorias', CategoriaController::class);

// Rutas para las unidades didácticas
Route::resource('unidades', UnidadDidacticaController::class);

Route::resource( 'usuarios', UsuarioController::class);

