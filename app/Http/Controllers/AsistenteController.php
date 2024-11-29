<?php
namespace App\Http\Controllers;

use App\Models\Asistente;
use Illuminate\Http\Request;

class AsistenteController extends Controller
{
    // Listar todos los asistentes
    public function index()
    {
        // Incluye la relación con los salones (muchos a muchos)
        $asistentes = Asistente::with('usuario', 'salones')->get();
        return view('asistentes.index', compact('asistentes'));
    }

    // Mostrar detalles de un asistente
    public function show($id_asistente)
    {
        // Incluye la relación con los salones (muchos a muchos)
        $asistente = Asistente::with('usuario', 'salones')->findOrFail($id_asistente);
        return view('asistentes.show', compact('asistente'));
    }

    // Crear un nuevo asistente
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'salones' => 'required|array', // Asegúrate de que sea un array de IDs de salones
            'salones.*' => 'exists:salones,id', // Verifica que cada ID de salón exista
            'turno' => 'required|in:mañana,noche',
        ]);

        // Crea el asistente
        $asistente = Asistente::create([
            'id_usuario' => $data['id_usuario'],
            'turno' => $data['turno'],
        ]);

        // Asocia los salones al asistente
        $asistente->salones()->attach($data['salones']);

        return redirect()->route('asistentes.index')->with('success', 'Asistente creado con éxito.');
    }

    // Eliminar un asistente
    public function destroy($id_asistente)
    {
        $asistente = Asistente::findOrFail($id_asistente);

        // Elimina las relaciones con los salones antes de eliminar el asistente
        $asistente->salones()->detach();

        $asistente->delete();

        return redirect()->route('asistentes.index')->with('success', 'Asistente eliminado con éxito.');
    }
}
