<?php

namespace App\Http\Controllers;

use App\Models\UnidadDidactica;
use Illuminate\Http\Request;

class UnidadDidacticaController extends Controller
{
    // Listar todas las unidades didácticas
    public function index()
    {
        $unidades = UnidadDidactica::all();
        return view('unidades.index', compact('unidades'));
    }

    // Mostrar el formulario de creación de una nueva unidad didáctica
    public function create()
    {
        return view('unidades.create');
    }

    // Guardar una nueva unidad didáctica
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:50',
            'ciclo' => 'required|string|max:50',
        ]);

        UnidadDidactica::create($data);

        return redirect()->route('unidades.index')->with('success', 'Unidad didáctica creada con éxito.');
    }

    // Mostrar el formulario de edición para una unidad didáctica
    public function edit($id_unidad_didactica)
    {
        $unidad = UnidadDidactica::findOrFail($id_unidad_didactica);
        return view('unidades.edit', compact('unidad'));
    }

    // Actualizar una unidad didáctica
    public function update(Request $request, $id_unidad_didactica)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:50',
            'ciclo' => 'required|string|max:50',
        ]);

        $unidad = UnidadDidactica::findOrFail($id_unidad_didactica);
        $unidad->update($data);

        return redirect()->route('unidades.index')->with('success', 'Unidad didáctica actualizada con éxito.');
    }

    // Eliminar una unidad didáctica
    public function destroy($id_unidad_didactica)
    {
        $unidad = UnidadDidactica::findOrFail($id_unidad_didactica);
        $unidad->delete();

        return redirect()->route('unidades.index')->with('success', 'Unidad didáctica eliminada con éxito.');
    }
}
