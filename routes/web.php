<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\ArmarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Ruta de inicio
Route::get('/', function () {
    return view('dashboard'); // Página de inicio para usuarios autenticados
})->middleware('auth');

// Ruta del dashboard
Route::get('/dashboard', function () {
    return view('dashboard'); // Asegúrate que el nombre de la vista es correcto
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas relacionadas con el perfil del usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Grupo de rutas protegidas para el rol admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('usuarios', UsuarioController::class)
        ->middleware('permission:ver usuarios|crear usuarios|editar usuarios|eliminar usuarios');
    Route::resource('items', ItemController::class)
        ->middleware('permission:ver items|crear items|editar items|eliminar items');
    Route::resource('salones', SalonController::class)
        ->middleware('permission:ver salones|crear salones|editar salones|eliminar salones');
    Route::resource('armarios', ArmarioController::class)
        ->middleware('permission:ver armarios|crear armarios|editar armarios|eliminar armarios');
    Route::resource('reservas', ReservaController::class)
        ->middleware('permission:ver reservas|crear reservas|editar reservas|eliminar reservas');
});

// Grupo de rutas para profesores (crear y gestionar sus propias reservas)
Route::middleware(['auth', 'role:profesor'])->group(function () {
    Route::get('reservas/create', action: [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('reservas', [ReservaController::class, 'index'])->name('reservas.index')
        ->middleware('permission:ver reservas'); // Solo los profesores pueden ver sus propias reservas
});

// Ruta para obtener armarios por salón (disponible para todos los usuarios autenticados)
Route::get('/salones/{id}/armarios', [SalonController::class, 'getArmariosBySalon'])->middleware('auth');

// Incluye las rutas de autenticación generadas por Breeze
require __DIR__.'/auth.php';
