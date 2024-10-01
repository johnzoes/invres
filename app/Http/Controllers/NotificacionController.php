<?php
namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    // Listar todas las notificaciones
    public function index()
    {
        $notificaciones = Notificacion::where('es_leida', false)
                                      ->with('reserva', 'asistente')
                                      ->get();

        return view('notificaciones.index', compact('notificaciones'));
    }

    // Marcar notificación como leída
    public function markAsRead($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        $notificacion->update(['es_leida' => true]);

        return redirect()->route('notificaciones.index')->with('success', 'Notificación marcada como leída.');
    }
}

