<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    // Listar todas las categorías
    public function index()
    {
        $categorias = Categoria::all();
        return view('categorias.index', compact('categorias'));
    }

    // Mostrar el formulario de creación de una nueva categoría
    public function create()
    {
        return view('categorias.form');
    }

    // Guardar una nueva categoría
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_categoria' => 'required|string|max:50',
        ]);

        Categoria::create($data);

        return redirect()->route('categorias.index')->with('success', 'Categoría creada con éxito.');
    }

    // Mostrar el formulario de edición para una categoría
    public function edit($id_categoria)
    {
        $categoria = Categoria::findOrFail($id_categoria);
        return view('categorias.form', compact('categoria'));
    }

    // Actualizar una categoría
    public function update(Request $request, $id_categoria)
    {
        $data = $request->validate([
            'nombre_categoria' => 'required|string|max:50',
        ]);

        $categoria = Categoria::findOrFail($id_categoria);
        $categoria->update($data);

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada con éxito.');
    }

    // Eliminar una categoría
    public function destroy($id_categoria)
    {
        $categoria = Categoria::findOrFail($id_categoria);
        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada con éxito.');
    }
}
