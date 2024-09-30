<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\DetalleReservaItem;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    // Listar todas las reservas
    public function index()
    {
        // Trae todas las reservas con sus detalles
        $reservas = Reserva::with('detalles.item')->get();
        return response()->json($reservas); // Devuelve las reservas como JSON
    }

    // Mostrar los detalles de una reserva específica
    public function show($id)
    {
        // Busca la reserva por su ID
        $reserva = Reserva::with('detalles.item')->find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        return response()->json($reserva); // Devuelve los detalles de la reserva
    }

    // Crear una nueva reserva
    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'id_profesor' => 'required|exists:profesor,id_profesor',
            'id_unidad_didactica' => 'required|exists:unidad_didactica,id_unidad_didactica',
            'items' => 'required|array',
            'items.*.id_item' => 'required|exists:item,id_item',
            'items.*.cantidad_reservada' => 'required|integer|min:1',
        ]);

        // Crear la reserva
        $reserva = Reserva::create([
            'fecha_prestamo' => now(),
            'id_profesor' => $request->id_profesor,
            'id_unidad_didactica' => $request->id_unidad_didactica,
        ]);

        // Guardar los ítems en la tabla detalle_reserva_item
        foreach ($request->items as $item) {
            DetalleReservaItem::create([
                'id_reserva' => $reserva->id,
                'id_item' => $item['id_item'],
                'cantidad_reservada' => $item['cantidad_reservada'],
                'estado' => 'pendiente',
                'fecha_reserva' => now(),
                'hora_reserva' => now(),
            ]);
        }

        return response()->json(['message' => 'Reserva creada con éxito', 'reserva' => $reserva], 201);
    }

    // Actualizar el estado de una reserva
    public function update(Request $request, $id)
    {
        // Busca la reserva
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        // Actualiza los detalles de la reserva
        $reserva->update($request->all());

        return response()->json(['message' => 'Reserva actualizada con éxito']);
    }

    // Eliminar una reserva
    public function destroy($id)
    {
        // Busca la reserva
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        // Elimina la reserva
        $reserva->delete();

        return response()->json(['message' => 'Reserva eliminada con éxito']);
    }
}
