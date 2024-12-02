<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Item;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $categorias = Categoria::withCount('items');
        
        if ($request->filter === 'mis_items' && auth()->user()->hasRole('asistente')) {
            $salonIds = auth()->user()->asistente->salones->pluck('id');
            $categorias->whereHas('items', function ($query) use ($salonIds) {
                $query->whereHas('armario', function ($q) use ($salonIds) {
                    $q->whereIn('id_salon', $salonIds);
                });
            });
        }
    
        $categorias = $categorias->get();
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_categoria' => 'required|string|max:50',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $categoria = new Categoria();
        $categoria->nombre_categoria = $data['nombre_categoria'];
        
        if ($request->hasFile('imagen')) {
            $categoria->imagen = $request->file('imagen')->store('categorias', 'public');
        }
        
        $categoria->save();

        return redirect()->route('categorias.index')->with('success', 'Categoría creada con éxito.');
    }

    public function edit($id_categoria)
    {
        $categoria = Categoria::findOrFail($id_categoria);
        return view('categorias.form', compact('categoria'));
    }

    public function update(Request $request, $id_categoria)
    {
        $data = $request->validate([
            'nombre_categoria' => 'required|string|max:50',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $categoria = Categoria::findOrFail($id_categoria);
        $categoria->nombre_categoria = $data['nombre_categoria'];

        if ($request->hasFile('imagen')) {
            if ($categoria->imagen) {
                Storage::disk('public')->delete($categoria->imagen);
            }
            $categoria->imagen = $request->file('imagen')->store('categorias', 'public');
        }

        $categoria->save();

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada con éxito.');
    }

    public function destroy($id_categoria)
    {
        $categoria = Categoria::findOrFail($id_categoria);
        
        if ($categoria->items()->count() > 0) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene items asociados.');
        }

        if ($categoria->imagen) {
            Storage::disk('public')->delete($categoria->imagen);
        }

        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada con éxito.');
    }
}