<?php

namespace App\Http\Controllers;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $notificaciones = auth()->check() 
        ? Notificacion::where('id_asistente', auth()->id())->where('es_leida', 0)->get()
        : collect();
    


        return view('dashboard', compact('notificaciones'));
    }
    
    
    
    
    
    
}

