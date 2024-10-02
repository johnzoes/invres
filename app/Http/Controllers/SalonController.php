<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use Illuminate\Http\Request;

class SalonController extends Controller
{
    // Listar todos los salones
    public function index()
    {
        $salones = Salon::all();
        return view('salones.index', compact('salones'));
    }

    // Mostrar el formulario de creación de un nuevo salón
    public function create()
    {
        return view('salones.form');
    }

    // Guardar un nuevo salón
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_salon' => 'required|string|max:100',
        ]);

        Salon::create($data);

        return redirect()->route('salones.index')->with('success', 'Salón creado con éxito.');
    }

    // Mostrar el formulario de edición para un salón
    public function edit($id_salon)
    {
        $salon = Salon::findOrFail($id_salon);
        return view('salones.form', compact('salon'));
    }

    // Actualizar un salón
    public function update(Request $request, $id_salon)
    {
        $data = $request->validate([
            'nombre_salon' => 'required|string|max:100',
        ]);

        $salon = Salon::findOrFail($id_salon);
        $salon->update($data);

        return redirect()->route('salones.index')->with('success', 'Salón actualizado con éxito.');
    }

    // Eliminar un salón
    public function destroy($id_salon)
    {
        $salon = Salon::findOrFail($id_salon);
        $salon->delete();

        return redirect()->route('salones.index')->with('success', 'Salón eliminado con éxito.');
    }
}
