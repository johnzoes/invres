<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $notificaciones = collect(); // Inicializar como colección vacía

        if (auth()->check()) {
            // Obtener el asistente asociado al usuario autenticado
            $asistente = auth()->user()->asistente;

            // Si el usuario tiene un asistente asociado, obtener sus notificaciones
            if ($asistente) {
                $notificaciones = Notificacion::where('id_asistente', $asistente->id)
                    ->where('es_leida', 0)
                    ->get();
            }
        }

        return view('dashboard', compact('notificaciones'));
    }
}
