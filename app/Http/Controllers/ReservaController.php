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
        $reservas = Reserva::with('detalles.item', 'profesor.usuario', 'unidadDidactica')->get();
        return view('reservas.index', compact('reservas'));
    }

    // Método para mostrar el formulario de creación de una nueva reserva
    public function create()
    {
        // Cargar los datos necesarios para el formulario
        $profesores = Profesor::all();  // Obtener todos los profesores
        $unidades_didacticas = UnidadDidactica::all();  // Obtener todas las unidades didácticas
        $items = Item::all();  // Obtener todos los ítems disponibles

        // Devolver la vista del formulario y pasar los datos necesarios
        return view('reservas.create', compact('profesores', 'unidades_didacticas', 'items'));
    }

    // Método para guardar la nueva reserva
    public function store(Request $request)
    {
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

        // Guardar los ítems en detalle_reserva_item
        foreach ($request->items as $item) {
            DetalleReservaItem::create([
                'id_reserva' => $reserva->id_reserva,
                'id_item' => $item['id_item'],
                'cantidad_reservada' => $item['cantidad_reservada'],
                'estado' => 'pendiente',
                'fecha_reserva' => now(),
                'hora_reserva' => now(),
            ]);
        }

        return redirect()->route('reservas.index')->with('success', 'Reserva creada con éxito.');
    }

    // Otros métodos...
}
