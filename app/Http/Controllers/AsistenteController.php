<?php
namespace App\Http\Controllers;

use App\Models\Asistente;
use Illuminate\Http\Request;

class AsistenteController extends Controller
{
    // Listar todos los asistentes
    public function index()
    {
        $asistentes = Asistente::with('usuario', 'salon')->get();
        return view('asistentes.index', compact('asistentes'));
    }

    // Mostrar detalles de un asistente
    public function show($id_asistente)
    {
        $asistente = Asistente::with('usuario', 'salon')->findOrFail($id_asistente);
        return view('asistentes.show', compact('asistente'));
    }

    // Crear un nuevo asistente
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_usuario' => 'required|exists:usuario,id_usuario',
            'id_salon' => 'required|exists:salon,id_salon',
            'turno' => 'required|in:mañana,noche',
        ]);

        Asistente::create($data);

        return redirect()->route('asistentes.index')->with('success', 'Asistente creado con éxito.');
    }

    // Eliminar un asistente
    public function destroy($id_asistente)
    {
        $asistente = Asistente::findOrFail($id_asistente);
        $asistente->delete();

        return redirect()->route('asistentes.index')->with('success', 'Asistente eliminado con éxito.');
    }
}
