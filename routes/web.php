<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



// Ruta de inicio
Route::get('/', function () {
    return view('dashboard'); // Página de inicio para usuarios autenticados
})->middleware('auth');

// Ruta del dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/test', function () {
    return 'Middleware Test Passed';
});

// Grupo de rutas para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas protegidas por rol 'admin' para la gestión de usuarios
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
});

// Incluye las rutas de autenticación generadas por Breeze
require __DIR__.'/auth.php';
