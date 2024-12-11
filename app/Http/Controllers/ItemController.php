<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use App\Models\Item;
use App\Models\Categoria;
use App\Models\Armario;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Listar todos los ítems
    public function index(Request $request)
    {
        $usuario = auth()->user();
        $query = Item::with('categoria', 'armario.salon');
        $showMyItems = $request->get('view', 'all') === 'my';
    
        // Filtro por categoría
        $categoriaActual = null;
        if ($request->has('categoria')) {
            $query->where('id_categoria', $request->categoria);
            $categoriaActual = Categoria::findOrFail($request->categoria);
        }
    
        // Filtrar según el rol y la vista seleccionada
        if ($usuario->hasRole('admin')) {
            $items = $query->get();
            $misItems = $items; // Para admins, todos los items son "sus items"
        } 
        elseif ($usuario->hasRole(['asistente', 'profesor'])) {
            $asistente = $usuario->asistente;
            
            if ($usuario->hasRole('asistente')) {
                $salonIds = $asistente->salones->pluck('id')->toArray();
                $salones = Salon::whereIn('id', $salonIds)->get();
                
                if ($showMyItems) {
                    $items = $query->whereHas('armario.salon', function ($query) use ($salonIds) {
                        $query->whereIn('id', $salonIds);
                    })->get();
                }
            }
            
            $items = $query->get();
        }
        else {
            return redirect()->back()->withErrors('No tienes permisos para ver esta página.');
        }
    
        return view('items.index', [
            'items' => $items,
            'categoriaActual' => $categoriaActual,
            'salones' => $salones ?? null,
            'showMyItems' => $showMyItems
        ]);
    }



    public function search(Request $request)
    {
        $query = $request->input('q', ''); // Obtén el término de búsqueda
    
        // Buscar ítems por descripción
        $items = Item::where('descripcion', 'LIKE', '%' . $query . '%')
                    ->orderBy('created_at', 'desc')
                    ->get();
    
        // Renderizar la tabla parcial y devolver como JSON
        $html = view('items.index-table', compact('items'))->render();
    
        return response()->json(['html' => $html]);
    }
    


    // Mostrar detalles de un ítem
    public function show($id_item)
    {
        $item = Item::with('categoria', 'armario.salon')->findOrFail($id_item);
        return view('items.show', compact('item'));
    }

    // Mostrar el formulario de creación de un nuevo ítem
    public function create(Request $request)
    {
        $showMyItems = $request->get('view', 'all') === 'my';
        $categoriaPreseleccionada = null;
        
        // Obtener el usuario actual
        $user = auth()->user();
        
        // Obtener las categorías
        $categorias = Categoria::orderBy('nombre_categoria')->get();
        
        // Obtener los salones según el rol
        if ($user->hasRole('admin')) {
            // El admin ve todos los salones
            $salones = Salon::orderBy('nombre_salon')->get();
        } elseif ($user->hasRole('asistente')) {
            // El asistente solo ve sus salones asignados
            $salonIds = $user->asistente->salones->pluck('id');
            $salones = Salon::whereIn('id', $salonIds)->orderBy('nombre_salon')->get();
        } else {
            // Para otros roles (como profesor), mostrar todos los salones
            $salones = Salon::orderBy('nombre_salon')->get();
        }
        
        // Verificar si viene de una categoría específica
        if ($request->has('categoria')) {
            $categoriaActual = Categoria::find($request->categoria);
            $categoriaPreseleccionada = $categoriaActual ? $categoriaActual->id : null;
        }
    
        return view('items.form', compact(
            'categorias',
            'salones',
            'categoriaPreseleccionada',
            'showMyItems'
        ));
    }


   public function store(Request $request)
{
    // Validar la solicitud
    $data = $request->validate([
        'codigo_bci' => 'nullable|string',
        'descripcion' => 'required|string',
        'cantidad' => 'required|integer|min:0',
        'tipo' => 'required|in:unidad,paquete',
        'marca' => 'nullable|string',
        'modelo' => 'nullable|string',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Aceptar WebP
        'nro_inventariado' => 'nullable|string',
        'id_categoria' => 'required|exists:categorias,id',
        'id_armario' => 'required|exists:armarios,id',
    ]);

    // Manejar la carga de la imagen solo si se proporciona
    if ($request->hasFile('imagen')) {
        $file = $request->file('imagen');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads', $fileName, 'public');
        $data['imagen'] = $filePath;
    }

    try {
        // Crear el nuevo ítem
        $item = Item::create($data);

        // Actualizar el estado del ítem basado en la cantidad y tipo
        $item->actualizarEstado();

        return redirect()->route('items.index')->with('success', 'Ítem creado con éxito.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors('Error al guardar el ítem: ' . $e->getMessage())->withInput();
    }
}




    // Mostrar el formulario de edición para un ítem
    public function edit($id_item)
    {
        $item = Item::findOrFail($id_item);
        $salones = Salon::all(); 
        $categorias = Categoria::all();
        $armarios = Armario::all();
        return view('items.form', compact('item', 'categorias', 'armarios', 'salones'));
    }

    // Actualizar un ítem existente
    public function update(Request $request, $id_item)
{
    $item = Item::findOrFail($id_item);

    // Validar la solicitud
    $data = $request->validate([
        'codigo_bci' => 'nullable|string',
        'descripcion' => 'required|string',
        'cantidad' => 'required|integer|min:0',
        'tipo' => 'required|in:unidad,paquete',
        'marca' => 'nullable|string',
        'modelo' => 'nullable|string',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Aceptar WebP
        'nro_inventariado' => 'nullable|string',
        'id_categoria' => 'required|exists:categorias,id',
        'id_armario' => 'required|exists:armarios,id',
    ]);

    // Manejar la carga de la imagen solo si se proporciona
    if ($request->hasFile('imagen')) {
        // Eliminar la imagen anterior si existe
        if ($item->imagen && file_exists(public_path('storage/' . $item->imagen))) {
            unlink(public_path('storage/' . $item->imagen));
        }

        $file = $request->file('imagen');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads', $fileName, 'public');
        $data['imagen'] = $filePath;
    }

    // Actualizar solo los campos que fueron cambiados
    $item->update($data);

    // Actualizar el estado del ítem basado en la cantidad y tipo
    $item->actualizarEstado();

    return redirect()->route('items.index')->with('success', 'Ítem actualizado con éxito.');
}



    // Eliminar un ítem
    public function destroy($id_item)
    {
        $item = Item::findOrFail($id_item);
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Ítem eliminado con éxito.');
    }

    // Obtener los armarios según el salón seleccionado (para uso en AJAX)
    public function getArmariosBySalon($salonId)
    {
        $armarios = Armario::where('id_salon', $salonId)->get();
        return response()->json($armarios);
    }
}
