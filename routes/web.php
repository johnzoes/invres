<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\ArmarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Ruta de inicio
Route::get('/', function () {
    return view('dashboard'); // Página de inicio para usuarios autenticados
})->middleware('auth')->name('home');

// Ruta del dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas relacionadas con el perfil del usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Grupo de rutas protegidas para el rol admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('usuarios', UsuarioController::class)->middleware('permission:ver usuarios|crear usuarios|editar usuarios|eliminar usuarios');
    Route::resource('items', ItemController::class)->middleware('permission:ver items|crear items|editar items|eliminar items');
    Route::resource('salones', SalonController::class)->middleware('permission:ver salones|crear salones|editar salones|eliminar salones');
    Route::resource('armarios', ArmarioController::class)->middleware('permission:ver armarios|crear armarios|editar armarios|eliminar armarios');
});

// Rutas para crear reservas (solo profesores y admins)
Route::middleware(['auth', 'role:profesor|admin'])->group(function () {
    Route::get('reservas/create', [ReservaController::class, 'create'])->name('reservas.create')
        ->middleware('permission:crear reservas');
    Route::post('reservas', [ReservaController::class, 'store'])->name('reservas.store')
        ->middleware('permission:crear reservas');
        Route::delete('reservas/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy')
        ->middleware('permission:eliminar reservas'); 
});

// Rutas para ver las reservas (profesores, asistentes y admins)
Route::middleware(['auth', 'role:profesor|asistente|admin'])->group(function () {
    Route::get('reservas', [ReservaController::class, 'index'])->name('reservas.index')
        ->middleware('permission:ver reservas');
        Route::get('reservas/{reserva}', [ReservaController::class, 'show'])->name('reservas.show')
        ->middleware('permission:ver reservas'); 
});

// Rutas para gestión avanzada de reservas (solo asistentes y admins)
Route::middleware(['auth', 'role:asistente|admin'])->group(function () {
    Route::post('reservas/aprobar', [ReservaController::class, 'approve'])->name('reservas.approve')
        ->middleware('permission:aprobar reservas');
    Route::post('reservas/rechazar', [ReservaController::class, 'reject'])->name('reservas.reject')
        ->middleware('permission:rechazar reservas');
    Route::post('reservas/prestar', [ReservaController::class, 'lend'])->name('reservas.lend')
        ->middleware('permission:prestar ítems');
    Route::post('reservas/devolver', [ReservaController::class, 'return'])->name('reservas.return')
        ->middleware('permission:devolver ítems');
});

// Ruta para obtener armarios por salón (disponible para todos los usuarios autenticados)
Route::get('/salones/{id}/armarios', [SalonController::class, 'getArmariosBySalon'])->middleware('auth')->name('salones.armarios');

// Incluye las rutas de autenticación generadas por Breeze
require __DIR__.'/auth.php';
