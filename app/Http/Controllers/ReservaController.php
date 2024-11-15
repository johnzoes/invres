<?php

namespace App\Http\Controllers;
use App\Models\Notificacion;
use App\Models\HistorialEstadoItem;
use App\Notifications\NotificacionReserva;
use App\Models\Reserva;
use App\Models\DetalleReservaItem;
use App\Models\Asistente;
use App\Models\Profesor;
use App\Models\UnidadDidactica;
use App\Models\Item;
use Illuminate\Http\Request;


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
        // Traer los profesores, unidades didácticas e ítems para mostrarlos en el formulario
        $profesores = Profesor::with('usuario')->get();
        $unidades_didacticas = UnidadDidactica::all();
        $items = Item::all();

        // Retornar la vista con los datos necesarios para la creación de la reserva
        return view('reservas.create', compact('profesores', 'unidades_didacticas', 'items'));
    }





    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'id_unidad_didactica' => 'required|exists:unidades_didacticas,id',
            'items' => 'required|array',
            'items.*' => 'exists:items,id',
            'cantidad.*' => 'required|integer|min:1', // Validar las cantidades
            'turno' => 'required|in:mañana,noche', // Validar el turno

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
            $cantidad = $request->input('cantidad.' . $itemId);
    
            // Crear el detalle de la reserva
            $detalleReserva = DetalleReservaItem::create([
                'id_reserva' => $reserva->id,
                'id_item' => $itemId,
                'fecha_reserva' => now(),
                'hora_reserva' => now(),
                'cantidad_reservada' => $cantidad,
                'estado' => 'pendiente',
            ]);
    
            // Obtener el armario del ítem para identificar el salón y el asistente correspondiente
            $item = Item::with('armario.salon')->find($itemId);
            if ($item && $item->armario && $item->armario->salon) {
                $salonId = $item->armario->salon->id;
    
            // Obtener el asistente responsable del salón y turno
            $asistente = Asistente::where('id_salon', $salonId)
                                  ->where('turno', $request->turno) // Filtrar por el turno seleccionado
                                  ->first();

                if ($asistente && !in_array($asistente->id, $asistentesNotificados) && $asistente->usuario) {
                    // Notificar al asistente a través de Laravel Notifications
                    $asistente->usuario->notify(new NotificacionReserva([
                        'mensaje' => "Nueva reserva de ítems en el salón $salonId",
                        'reserva_id' => $reserva->id,
                        'usuario_remitente' => auth()->user()->nombre,
                        'usuario_destinatario' => $asistente->usuario->nombre,
                        'turno' => $request->turno,

                    ]));
    
                    // Agregar el asistente al array para evitar notificaciones duplicadas
                    $asistentesNotificados[] = $asistente->id;
                }
            }
        }
    
        return redirect()->route('reservas.index')->with('success', 'Reserva creada con éxito y notificaciones enviadas a los asistentes.');
    }
    

    // Mostrar los detalles de una reserva específica
    public function show($id)
    {
        // Busca la reserva por su ID
        $reserva = Reserva::with('detalles.item', 'profesor.usuario', 'unidadDidactica')->find($id);

        if (!$reserva) {
            return redirect()->route('reservas.index')->with('error', 'Reserva no encontrada.');
        }

        return view('reservas.show', compact('reserva'));
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

                return redirect()->back()->with('success', 'Ítem devuelto.');
            }

            return redirect()->back()->with('error', 'No se puede devolver este ítem.');
        }


}
