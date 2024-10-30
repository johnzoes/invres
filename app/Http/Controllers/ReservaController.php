<?php

namespace App\Http\Controllers;
use App\Notifications\NotificacionReserva;
use App\Models\Notificacion;

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
        ]);
    
        // Array para almacenar los IDs de los asistentes notificados
    
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
    
                // Obtener el asistente responsable del salón
                $asistente = Asistente::where('id_salon', $salonId)->first();
                if ($asistente && !in_array($asistente->id, $asistentesNotificados) && $asistente->usuario) {
                    // Crear el mensaje de la notificación
                    $notificationData = [
                        'id_asistente' => $asistente->id,
                        'id_reserva' => $reserva->id,
                        'mensaje' => "Nueva reserva de ítems en el salón $salonId",
                        'es_leida' => 0, // Marcar como no leída
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
    
                 Notificacion::create($notificationData);

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
}
