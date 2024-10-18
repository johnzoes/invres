<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\DetalleReservaItem;
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
    
        // Si el usuario es un profesor, accedemos a sus reservas a través del modelo Profesor
        if ($user->hasRole('profesor')) {
            $profesor = $user->profesor; // Relación definida en el modelo Usuario (Usuario tiene un Profesor)
            $reservas = $profesor ? $profesor->reservas : []; // Si es profesor, obtiene sus reservas
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

    // Guardar una nueva reserva
    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'id_unidad_didactica' => 'required|exists:unidades_didacticas,id',
            'items' => 'required|array',
            'items.*' => 'exists:items,id',
            'cantidad.*' => 'required|integer|min:1', // Validar las cantidades
        ]);
    
        // Crear la reserva
        $reserva = Reserva::create([
            'fecha_prestamo' => now(),
            'id_profesor' => auth()->user()->profesor->id, // O id_profesor en la solicitud
            'id_unidad_didactica' => $request->id_unidad_didactica,
        ]);
    
        // Guardar los ítems y cantidades en la tabla detalle_reserva_item
        foreach ($request->items as $itemId) {
            $cantidad = $request->input('cantidad.' . $itemId);
    
            DetalleReservaItem::create([
                'id_reserva' => $reserva->id,
                'id_item' => $itemId,
                'cantidad_reservada' => $cantidad,
                'estado' => 'pendiente',
                'fecha_reserva' => now(),
                'hora_reserva' => now(),
            ]);
        }
    
        return redirect()->route('reservas.index')->with('success', 'Reserva creada con éxito.');
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
