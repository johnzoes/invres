<?php

namespace App\Http\Controllers;
use App\Models\Notificacion;
use App\Models\HistorialEstadoItem;
use App\Notifications\NotificacionReserva;
use App\Models\Reserva;
use App\Models\DetalleReservaItem;
use App\Models\Asistente;
use App\Models\Profesor;
use App\Models\Categoria;
use Illuminate\Support\Facades\Cache;

use App\Models\UnidadDidactica;
use App\Models\Item;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Events\NotificationEvent;


class ReservaController extends Controller
{

    // Método para listar todas las reservas
    public function index()
    {
        $user = auth()->user();
        $query = Reserva::with(['profesor.usuario', 'unidadDidactica', 'detalles.historialEstados']);
    
        // Filtrar según el rol
        if ($user->hasRole('profesor')) {
            $query->whereHas('profesor.usuario', function ($q) use ($user) {
                $q->where('id', $user->id);
            });
        } elseif ($user->hasRole('asistente')) {
            $salonIds = $user->asistente->salones->pluck('id');
            $query->whereHas('detalles.item.armario.salon', function ($q) use ($salonIds) {
                $q->whereIn('id_salon', $salonIds);
            });
        }
    
        $reservas = $query->latest()->get();
    
        // Obtener estadísticas basadas en los estados de los detalles
        $stats = [
            'total' => $reservas->count(),
            'pendientes' => $reservas->filter(function($reserva) {
                return $reserva->detalles->contains(function($detalle) {
                    return $detalle->estado === 'pendiente';
                });
            })->count(),
            'aceptados' => $reservas->filter(function($reserva) {
                return $reserva->detalles->contains(function($detalle) {
                    return $detalle->estado === 'aceptado';
                });
            })->count(),
            'rechazados' => $reservas->filter(function($reserva) {
                return $reserva->detalles->contains(function($detalle) {
                    return $detalle->estado === 'rechazado';
                });
            })->count()
        ];
    
        return view('reservas.index', compact('reservas', 'stats'));
    }
    
    

    // Mostrar el formulario para crear una nueva reserva
    public function create()
    {
        $categorias = Categoria::with(['items' => function($query) {
            $query->where('estado', 'like', 'disponible%')
                  ->select('id', 'descripcion', 'cantidad', 'tipo', 'estado', 'marca', 'modelo', 'id_categoria');
        }])->get();
    
        return view('reservas.create', compact('categorias'));
    }
    

    public function createtwo(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:categorias,id',
            'cantidad' => 'required|array',
            'cantidad.*' => 'required|integer|min:1',
        ]);
    
        // Crear array de items seleccionados
        $itemsSeleccionados = [];
        foreach ($request->items as $categoriaId) {
            $itemsSeleccionados[$categoriaId] = $request->input('cantidad.' . $categoriaId);
        }
    
        // Almacenar en la sesión
        session(['items' => $itemsSeleccionados]);
    
        // Cargar las unidades didácticas
        $unidades_didacticas = UnidadDidactica::all();
    
        return view('reservas.create-two', compact('unidades_didacticas'));
    }




    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'id_unidad_didactica' => 'required|exists:unidades_didacticas,id',
            'categorias' => 'required|array',
            'categorias.*' => 'exists:categorias,id',
            'cantidad' => 'required|array',
            'cantidad.*' => 'required|integer|min:1',
            'turno' => 'required|in:mañana,noche',
        ]);
    
        $asistentesNotificados = [];
    
        // Obtener el profesor autenticado
        $profesor = auth()->user()->profesor;
        if (!$profesor) {
            return redirect()->route('reservas.create')
                ->with('error', 'No tienes un profesor asociado.');
        }
    
        // Crear la reserva
        $reserva = Reserva::create([
            'fecha_prestamo' => now(),
            'id_profesor' => $profesor->id,
            'id_unidad_didactica' => $request->id_unidad_didactica,
            'turno' => $request->turno,
        ]);
    
        // Procesar items por categoría
        foreach ($request->categorias as $categoriaId) {
            $cantidadSolicitada = $request->input('cantidad.' . $categoriaId);
            
            // Obtener items de la categoría
            $items = Item::where('id_categoria', $categoriaId)
                ->where('estado', 'disponible')
                ->get();
    
            $cantidadRestante = $cantidadSolicitada;
            
            foreach ($items as $item) {
                if ($cantidadRestante <= 0) break;
    
                if ($item->tipo === 'unidad') {
                    // Para items unitarios, crear un detalle por cada unidad
                    DetalleReservaItem::create([
                        'id_reserva' => $reserva->id,
                        'id_item' => $item->id,
                        'fecha_reserva' => now(),
                        'hora_reserva' => now(),
                        'cantidad_reservada' => 1,
                        'estado' => 'pendiente',
                    ]);
                    $cantidadRestante--;
                } else {
                    // Para items tipo paquete
                    $cantidadAReservar = min($cantidadRestante, $item->cantidad);
                    DetalleReservaItem::create([
                        'id_reserva' => $reserva->id,
                        'id_item' => $item->id,
                        'fecha_reserva' => now(),
                        'hora_reserva' => now(),
                        'cantidad_reservada' => $cantidadAReservar,
                        'estado' => 'pendiente',
                    ]);
                    $cantidadRestante -= $cantidadAReservar;
                }
    
                // Notificar al asistente del salón
                if ($item->armario && $item->armario->salon) {
                    $salonId = $item->armario->salon->id;
                    $asistente = Asistente::whereHas('salones', function ($query) use ($salonId) {
                        $query->where('salones.id', $salonId);
                    })->where('turno', $request->turno)->first();
    
                    if ($asistente && !in_array($asistente->id, $asistentesNotificados) && $asistente->usuario) {
                        $asistente->usuario->notify(new NotificacionReserva([
                            'mensaje' => "Nueva reserva de items en el salón {$item->armario->salon->nombre_salon}",
                            'reserva_id' => $reserva->id,
                            'usuario_remitente' => auth()->user()->nombre,
                            'usuario_destinatario' => $asistente->usuario->nombre,
                            'turno' => $request->turno,
                        ]));
    
                        $asistentesNotificados[] = $asistente->id;
                        event(new NotificationEvent($reserva));
                    }
                }
            }
        }
    
        // Limpiar la sesión de items
        session()->forget('items');
    
        return redirect()->route('reservas.index')
            ->with('success', 'Reserva creada exitosamente');
    }
    
    

    public function show($id)
    {
        // Obtener la reserva junto con sus relaciones
        $reserva = Reserva::with('detalles.item.armario.salon', 'profesor.usuario', 'unidadDidactica')->find($id);
    
        if (!$reserva) {
            return redirect()->route('reservas.index')->with('error', 'Reserva no encontrada.');
        }
    
        // Obtener el usuario autenticado y verificar si es un asistente
        $asistente = auth()->user()->asistente;
    
        if ($asistente) {
            // Filtrar los detalles de la reserva que están bajo el control del asistente
            $detallesFiltrados = $reserva->detalles->filter(function ($detalle) use ($asistente, $reserva) {
                $salon = $detalle->item->armario->salon;
    
                // Verificar si el salón está entre los salones asignados al asistente y si el turno coincide
                return $salon && $asistente->salones->contains('id', $salon->id) && $asistente->turno == $reserva->turno;
            });
        } else {
            // Si no es asistente, no tiene permisos para gestionar ítems
            $detallesFiltrados = collect();
        }
    
        // Pasar solo los detalles filtrados a la vista
        return view('reservas.show', compact('reserva', 'detallesFiltrados'));
    }
    
    

    // Eliminar una reserva
    public function destroy($id)
    {
        // Busca la reserva
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return redirect()->route('reservas.index')->with('error', 'Reserva no encontrada.');
        }

        // Elimina la reserva
        $reserva->delete();

        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada con éxito.');
    }




    public function approve($detalleId)
    {
        try {
            // Encontrar el detalle de la reserva
            $detalle = DetalleReservaItem::findOrFail($detalleId);
    
            // Registrar el cambio en HistorialEstadoItem
            HistorialEstadoItem::create([
                'id_detalle_reserva_item' => $detalle->id,
                'estado' => 'aceptado',
                'fecha_estado' => now(),
            ]);

        // Reducir la cantidad en el inventario del ítem y actualizar su estado
        $item = $detalle->item;
        if ($item) {
            $item->cantidad -= $detalle->cantidad_reservada;
            $item->actualizarEstado(); // Actualiza el estado del ítem ('ocupado' si está agotado)
            $item->save();
        }
    
            // Actualizar el estado del detalle de la reserva
            $detalle->update(['estado' => 'aceptado']);
    
            // Verificar que la reserva, el profesor y el usuario existan
            if ($detalle->reserva && $detalle->reserva->profesor && $detalle->reserva->profesor->usuario) {
                $usuarioProfesor = $detalle->reserva->profesor->usuario;
                $usuarioRemitente = auth()->user();
    
                // Verificar que el usuario tenga un email válido
                if ($usuarioProfesor->email) {
                    // Notificar al profesor
                    $usuarioProfesor->notify(new NotificacionReserva([
                        'mensaje' => 'Tu reserva ha sido aprobada',
                        'reserva_id' => $detalle->reserva->id,
                        'usuario_remitente' => $usuarioRemitente->nombre,
                        'usuario_destinatario' => $usuarioProfesor->nombre,
                    ]));
                }
            }
    
            return redirect()->route('reservas.show', $detalle->id_reserva)->with('success', 'Ítem aprobado.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al aprobar la reserva.');
        }
    }
    
    



// Rechazar el ítem específico de la reserva
        public function reject(Request $request, $detalleId)
        {
            // Encontrar el detalle de la reserva específico por su ID
            $detalle = DetalleReservaItem::findOrFail($detalleId);

            // Registrar el cambio en HistorialEstadoItem con motivo de rechazo
            HistorialEstadoItem::create([
                'id_detalle_reserva_item' => $detalle->id,
                'estado' => 'rechazado',
                'motivo_rechazo' => $request->motivo_rechazo,
                'fecha_estado' => now(),
            ]);

            // Actualizar el estado del detalle de la reserva
            $detalle->update(['estado' => 'rechazado']);

            return redirect()->route('reservas.show', $detalle->id_reserva)->with('error', 'Ítem rechazado.');
        }

        // Prestar físicamente el ítem específico de la reserva
        public function lend($detalleId)
        {
            $detalle = DetalleReservaItem::findOrFail($detalleId);

            if ($detalle->estado == 'aceptado') {
                $detalle->update(['estado' => 'prestado']);

                // Registrar el cambio en HistorialEstadoItem
                HistorialEstadoItem::create([
                    'id_detalle_reserva_item' => $detalle->id,
                    'estado' => 'prestado',
                    'fecha_estado' => now(),
                ]);

                return redirect()->back()->with('success', 'Ítem prestado físicamente.');
            }

            return redirect()->back()->with('error', 'No se puede prestar este ítem.');
        }

        // Registrar devolución del ítem específico de la reserva
        public function return($detalleId)
        {
            $detalle = DetalleReservaItem::findOrFail($detalleId);

            if ($detalle->estado == 'prestado') {
                $detalle->update(['estado' => 'devuelto']);

                // Registrar el cambio en HistorialEstadoItem
                HistorialEstadoItem::create([
                    'id_detalle_reserva_item' => $detalle->id,
                    'estado' => 'devuelto',
                    'fecha_estado' => now(),
                ]);

                        // Aumentar la cantidad en el inventario del ítem y actualizar su estado
                $item = $detalle->item;
                if ($item) {
                    $item->cantidad += $detalle->cantidad_reservada;
                    $item->actualizarEstado(); // Actualiza el estado del ítem ('disponible' si hay stock)
                    $item->save();
                }

                return redirect()->back()->with('success', 'Ítem devuelto.');
            }

            return redirect()->back()->with('error', 'No se puede devolver este ítem.');
        }



        public function generarPDF($id)
        {
            // Cargar la reserva con sus relaciones
            $reserva = Reserva::with('profesor.usuario', 'unidadDidactica', 'detalles.item')->findOrFail($id);
        
            // Obtener el profesor desde la relación
            $profesor = $reserva->profesor;
        
            // Verificar si la reserva tiene un profesor asociado
            if (!$profesor) {
                return redirect()->back()->with('error', 'No se pudo encontrar el profesor asociado a esta reserva.');
            }
        
            // Cargar los detalles de la reserva
            $detalles = $reserva->detalles;
        
            // Generar el PDF con la vista y pasar las variables necesarias
            $pdf = Pdf::loadView('reservas.solicitud_pdf', compact('reserva', 'detalles', 'profesor'));
        
            // Descargar el PDF
            return $pdf->download('solicitud_reserva_' . $reserva->id . '.pdf');
        }


        public function obtenerReservas($profesorId = null)
        {
            // Consulta base para obtener todas las reservas con sus relaciones
            $queryBase = Reserva::with(['unidadDidactica', 'detalles.item', 'profesor']);
        
            // Si se proporciona un ID de profesor, filtramos por él
            if ($profesorId) {
                $queryBase->where('id_profesor', $profesorId);
            }
        
            // Agrupamos las reservas por estado en una sola consulta
            $reservas = $queryBase->whereHas('detalles', function ($query) {
                $query->whereIn('estado', ['pendiente', 'aceptado', 'prestado', 'devuelto']);
            })->get();
        
            // Inicializamos un arreglo para clasificar las reservas por estado
            $resultados = [
                'reservasPendientes' => [],
                'reservasAprobadas' => [],
                'reservasPrestadas' => [],
                'reservasDevueltas' => []
            ];
        
            // Clasificamos las reservas según su estado
            foreach ($reservas as $reserva) {
                foreach ($reserva->detalles as $detalle) {
                    switch ($detalle->estado) {
                        case 'pendiente':
                            $resultados['reservasPendientes'][] = $reserva;
                            break;
                        case 'aceptado':
                            $resultados['reservasAprobadas'][] = $reserva;
                            break;
                        case 'prestado':
                            $resultados['reservasPrestadas'][] = $reserva;
                            break;
                        case 'devuelto':
                            $resultados['reservasDevueltas'][] = $reserva;
                            break;
                    }
                }
            }
        
            return $resultados;
        }
        



}
