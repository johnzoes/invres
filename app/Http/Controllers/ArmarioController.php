<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use App\Models\Armario;
use Illuminate\Http\Request;

class ArmarioController extends Controller
{
    // Listar todos los armarios
    public function index()
    {
        $armarios = Armario::with('salon')->get();
        return view('armarios.index', compact('armarios'));
    }

    // Mostrar el formulario de creación de un nuevo armario
    public function create()
    {
        $salones = Salon::all();
        return view('armarios.form', compact('salones'));
    }

    // Guardar un nuevo armario
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_armario' => 'required|string|max:100',
            'id_salon' => 'required|exists:salon,id_salon',
        ]);

        Armario::create($data);

        return redirect()->route('armarios.index')->with('success', 'Armario creado con éxito.');
    }

    // Mostrar el formulario de edición para un armario
    public function edit($id_armario)
    {
        $armario = Armario::findOrFail($id_armario);
        $salones = Salon::all(); // Pasar los salones disponibles para la edición
        return view('armarios.form', compact('armario', 'salones'));
    }

    // Actualizar un armario
    public function update(Request $request, $id_armario)
    {
        $data = $request->validate([
            'nombre_armario' => 'required|string|max:100',
            'id_salon' => 'required|exists:salon,id_salon',
        ]);

        $armario = Armario::findOrFail($id_armario);
        $armario->update($data);

        return redirect()->route('armarios.index')->with('success', 'Armario actualizado con éxito.');
    }

    // Eliminar un armario
    public function destroy($id_armario)
    {
        $armario = Armario::findOrFail($id_armario);
        $armario->delete();

        return redirect()->route('armarios.index')->with('success', 'Armario eliminado con éxito.');
    }

    // Mostrar detalles de un armario específico
    public function show($id_armario)
    {
        $armario = Armario::with('salon')->findOrFail($id_armario);
        return view('armarios.show', compact('armario'));
    }
}
