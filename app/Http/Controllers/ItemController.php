<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Categoria;
use App\Models\Armario;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Listar todos los ítems
    public function index()
    {
        $items = Item::with('categoria', 'armario.salon')->get();
        return view('items.index', compact('items'));
    }

    // Mostrar detalles de un ítem
    public function show($id_item)
    {
        $item = Item::with('categoria', 'armario.salon')->findOrFail($id_item);
        return view('items.show', compact('item'));
    }

    // Mostrar el formulario de creación de un nuevo ítem
    public function create()
    {
        $categorias = Categoria::all();  // Cargar todas las categorías disponibles
        $armarios = Armario::all();  // Cargar todos los armarios disponibles
        return view('items.form', compact('categorias', 'armarios'));
    }

    // Crear un nuevo ítem
    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo_bci' => 'nullable|string',
            'descripcion' => 'required|string',
            'cantidad' => 'required|integer',
            'tipo' => 'required|in:unidad,paquete',
            'marca' => 'nullable|string',
            'modelo' => 'nullable|string',
            'imagen' => 'nullable|string',  // Asumiendo que la imagen se guarda como una URL o nombre de archivo
            'nro_inventariado' => 'nullable|string',
            'id_categoria' => 'required|exists:categorias,id',
            'id_armario' => 'required|exists:armarios,id',
        ]);

        Item::create($data);

        return redirect()->route('items.index')->with('success', 'Ítem creado con éxito.');
    }

    // Mostrar el formulario de edición para un ítem
    public function edit($id_item)
    {
        $item = Item::findOrFail($id_item);
        $categorias = Categoria::all();  // Para cargar las categorías disponibles
        $armarios = Armario::all();  // Para cargar los armarios disponibles
        return view('items.form', compact('item', 'categorias', 'armarios'));
    }

    // Actualizar un ítem existente
    public function update(Request $request, $id_item)
    {
        $data = $request->validate([
            'codigo_bci' => 'nullable|string',
            'descripcion' => 'required|string',
            'cantidad' => 'required|integer',
            'tipo' => 'required|in:unidad,paquete',
            'marca' => 'nullable|string',
            'modelo' => 'nullable|string',
            'imagen' => 'nullable|string',  // Asumiendo que la imagen se guarda como una URL o nombre de archivo
            'nro_inventariado' => 'nullable|string',
            'id_categoria' => 'required|exists:categoria,id_categoria',
            'id_armario' => 'required|exists:armario,id_armario',
        ]);

        $item = Item::findOrFail($id_item);
        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Ítem actualizado con éxito.');
    }

    // Eliminar un ítem
    public function destroy($id_item)
    {
        $item = Item::findOrFail($id_item);
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Ítem eliminado con éxito.');
    }
}
