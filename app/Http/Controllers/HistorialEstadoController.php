<?php


namespace App\Http\Controllers;

use App\Models\HistorialEstadoItem;
use Illuminate\Http\Request;

class HistorialEstadoController extends Controller
{
    public function show($detalleReservaItemId)
    {

        $historial = HistorialEstadoItem::where('id_detalle_reserva_item', $detalleReservaItemId)
            ->orderBy('fecha_estado', 'asc')
            ->get();

            $fechaPrestamo = $historial->first()?->detalleReservaItem?->reserva?->created_at;

            return view('historial.index', compact('historial', 'fechaPrestamo'));
        }
}
