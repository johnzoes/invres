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

use App\Models\UnidadDidactica;
use App\Models\Item;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class ReservaController extends Controller
{

    // Método para listar todas las reservas
    public function index()
    {
        $user = auth()->user();
        // Si el usuario es un profesor, accedemos a sus reservas
        if ($user->hasRole('profesor')) {
            $profesor = $user->profesor;
            $reservas = $profesor ? $profesor->reservas : [];
        } else {
            // Si es admin o asistente, mostramos todas las reservas
            $reservas = Reserva::all();
        }
    
        return view('reservas.index', compact('reservas'));
    }


    
    

    // Mostrar el formulario para crear una nueva reserva
    public function create()
{
    // Traer los profesores, categorías y ítems relacionados
    $profesores = Profesor::with('usuario')->get();
    $categorias = Categoria::all();

    $items = Item::with('categoria')
                ->where('cantidad', '>', 0)
                ->get();
    // Retornar la vista con los datos necesarios para la creación de la reserva
    return view('reservas.create', compact('profesores', 'categorias', 'items'));
}

public function createtwo(Request $request)
{
    // Validar la solicitud
    $request->validate([
        'items' => 'required|array',
        'items.*' => 'exists:items,id',
        'cantidad.*' => 'required|integer|min:1',
    ]);

    // Guardar los ítems seleccionados y sus cantidades en la sesión
    $itemsSeleccionados = [];
    foreach ($request->items as $itemId) {
        $itemsSeleccionados[$itemId] = $request->input('cantidad.' . $itemId);
    }

    // Almacenar en la sesión
    session(['items' => $itemsSeleccionados]);

    // Cargar las unidades didácticas para la siguiente vista
    $unidades_didacticas = UnidadDidactica::all();

    return view('reservas.create-two', compact('unidades_didacticas'));
}



    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'id_unidad_didactica' => 'required|exists:unidades_didacticas,id',
            'items' => 'required|array',
            'items.*' => 'exists:items,id',
            'cantidad.*' => 'required|integer|min:1',
            'turno' => 'required|in:mañana,noche',
        ]);
    
        $asistentesNotificados = [];
    
        // Obtener el profesor autenticado
        $profesor = auth()->user()->profesor;
        if (!$profesor) {
            return redirect()->route('reservas.create')->withErrors('No tienes un profesor asociado.');
        }
    
        // Crear la reserva
        $reserva = Reserva::create([
            'fecha_prestamo' => now(),
            'id_profesor' => $profesor->id,
            'id_unidad_didactica' => $request->id_unidad_didactica,
            'turno' => $request->turno,
        ]);
    
        // Guardar los ítems y cantidades en la tabla detalle_reserva_item y notificar a los asistentes
        foreach ($request->items as $itemId) {

            // Obtener la cantidad de ítems solicitados
            $cantidad = $request->input('cantidad.' . $itemId);
    
            // Obtener el ítem desde la base de datos
            $item = Item::find($itemId);
            if ($item) {
                // Verificar si hay suficiente cantidad en el inventario
                if ($item->cantidad < $cantidad) {
                    return redirect()->route('reservas.create')->withErrors("No hay suficiente stock para el ítem: {$item->descripcion}");
                }
    
    
                // Crear el detalle de la reserva
                $detalleReserva = DetalleReservaItem::create([
                    'id_reserva' => $reserva->id,
                    'id_item' => $itemId,
                    'fecha_reserva' => now(),
                    'hora_reserva' => now(),
                    'cantidad_reservada' => $cantidad,
                    'estado' => 'pendiente',
                ]);
    
                // Obtener el armario del ítem para notificar al asistente
                if ($item->armario && $item->armario->salon) {
                    $salonId = $item->armario->salon->id;
    
                    // Obtener el asistente responsable del salón y turno
                    $asistente = Asistente::where('id_salon', $salonId)
                                          ->where('turno', $request->turno)
                                          ->first();
    
                    if ($asistente && !in_array($asistente->id, $asistentesNotificados) && $asistente->usuario) {
                        // Notificar al asistente
                        $asistente->usuario->notify(new NotificacionReserva([
                            'mensaje' => "Nueva reserva de ítems en el salón $salonId",
                            'reserva_id' => $reserva->id,
                            'usuario_remitente' => auth()->user()->nombre,
                            'usuario_destinatario' => $asistente->usuario->nombre,
                            'turno' => $request->turno,
                        ]));
                        $asistentesNotificados[] = $asistente->id;
                    }
                }
            }
        }
    
        return redirect()->route('reservas.index')->with('success', 'Reserva creada con éxito y notificaciones enviadas a los asistentes.');
    }
    
    

    // Mostrar los detalles de una reserva específica
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
            return $salon && $salon->id == $asistente->id_salon && $asistente->turno == $reserva->turno;
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
