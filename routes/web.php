<?php
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\ArmarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ProfileController;
use App\Models\Salon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rutas que requieren autenticación
Route::middleware('auth')->group(function () {

    // Ruta de inicio y dashboard
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/salones/{id}/armarios', [SalonController::class, 'getArmariosBySalon'])->name('salones.armarios');
    
// Rutas relacionadas con el perfil del usuario autenticado
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('destroy');
});

    // Rutas para la gestión de usuarios (accesibles por admin, profesor y asistente, con permisos gestionados por middleware)
    Route::resource('usuarios', UsuarioController::class)->middleware('permission:ver usuarios|crear usuarios|editar usuarios|eliminar usuarios');

    // Rutas para la gestión de ítems
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index')->middleware('permission:ver items');
        Route::get('/create', [ItemController::class, 'create'])->name('create')->middleware('permission:crear items');
        Route::get('/{item}', [ItemController::class, 'show'])->name('show')->middleware('permission:ver items');
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('edit')->middleware('permission:editar items'); // Añadir esta línea
        Route::post('/', [ItemController::class, 'store'])->name('store')->middleware('permission:crear items');
        Route::put('/{item}', [ItemController::class, 'update'])->name('update')->middleware('permission:editar items');
        Route::delete('/{item}', [ItemController::class, 'destroy'])->name('destroy')->middleware('permission:eliminar items');
    });
    

// Rutas para la gestión de salones
Route::prefix('salones')->name('salones.')->group(function () {
    Route::get('/', [SalonController::class, 'index'])->name('index')->middleware('permission:ver salones');
    Route::get('/create', [SalonController::class, 'create'])->name('create')->middleware('permission:crear salones'); // Ruta para crear salones
    Route::get('/{salon}', [SalonController::class, 'show'])->name('show')->middleware('permission:ver salones');
    Route::get('/{salon}/edit', [SalonController::class, 'edit'])->name('edit')->middleware('permission:editar salones'); // Ruta para editar salones
    Route::post('/', [SalonController::class, 'store'])->name('store')->middleware('permission:crear salones');
    Route::put('/{salon}', [SalonController::class, 'update'])->name('update')->middleware('permission:editar salones');
    Route::delete('/{salon}', [SalonController::class, 'destroy'])->name('destroy')->middleware('permission:eliminar salones');
});


// Rutas para la gestión de armarios
Route::prefix('armarios')->name('armarios.')->group(function () {
    Route::get('/', [ArmarioController::class, 'index'])->name('index')->middleware('permission:ver armarios');
    Route::get('/create', [ArmarioController::class, 'create'])->name('create')->middleware('permission:crear armarios'); // Ruta para crear armarios
    Route::get('/{armario}', [ArmarioController::class, 'show'])->name('show')->middleware('permission:ver armarios');
    Route::get('/{armario}/edit', [ArmarioController::class, 'edit'])->name('edit')->middleware('permission:editar armarios'); // Ruta para editar armarios
    Route::post('/', [ArmarioController::class, 'store'])->name('store')->middleware('permission:crear armarios');
    Route::put('/{armario}', [ArmarioController::class, 'update'])->name('update')->middleware('permission:editar armarios');
    Route::delete('/{armario}', [ArmarioController::class, 'destroy'])->name('destroy')->middleware('permission:eliminar armarios');
});


    // Rutas para la gestión de reservas
    Route::prefix('reservas')->name('reservas.')->group(function () {
        Route::get('/', [ReservaController::class, 'index'])->name('index')->middleware('permission:ver reservas');
        Route::get('/create', [ReservaController::class, 'create'])->name('create')->middleware('permission:crear reservas');
        Route::post('/', [ReservaController::class, 'store'])->name('store')->middleware('permission:crear reservas');
        Route::get('/{reserva}', [ReservaController::class, 'show'])->name('show')->middleware('permission:ver reservas');
        Route::put('/{reserva}', [ReservaController::class, 'update'])->name('update')->middleware('permission:editar reservas');
        Route::delete('/{reserva}', [ReservaController::class, 'destroy'])->name('destroy')->middleware('permission:eliminar reservas');

        // Simplificación de nombres de acciones
        Route::post('/{reserva}/approve', [ReservaController::class, 'approve'])->name('approve')->middleware('permission:aprobar reservas');
        Route::post('/{reserva}/reject', [ReservaController::class, 'reject'])->name('reject')->middleware('permission:rechazar reservas');
        Route::post('/{reserva}/lend', [ReservaController::class, 'lend'])->name('lend')->middleware('permission:prestar ítems');
        Route::post('/{reserva}/return', [ReservaController::class, 'return'])->name('return')->middleware('permission:devolver ítems');
        
    });



    Route::middleware(['auth', 'permission:ver notificaciones'])
    ->prefix('notificaciones')
    ->name('notificaciones.')
    ->group(function () {
        
        // Ruta para ver todas las notificaciones
        Route::get('/', function () {
            return view('notificaciones.index', [
                'notificaciones' => auth()->user()->notifications
            ]);
        })->name('index');
    
        // Ruta para marcar una notificación como leída
        Route::post('/marcar-leida/{id}', function ($id) {
            $notificacion = Auth::user()->notifications()->find($id);
            
            if ($notificacion) {
                $notificacion->markAsRead();
            }
            
            return redirect()->back()->with('success', 'Notificación marcada como leída.');
        })->name('marcarLeida');
    });

});

// Cargar las rutas de autenticación
require __DIR__.'/auth.php';


