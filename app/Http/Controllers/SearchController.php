<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Item;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search(Request $request, string $type)
    {
        try {
            $query = $request->get('q');
            
            $searchTypes = [
                'usuarios' => [
                    'model' => Usuario::class,
                    'fields' => ['nombre', 'apellidos', 'nombre_usuario', 'email'],
                    'relations' => ['roles']
                ],
                'items' => [
                    'model' => Item::class,
                    'fields' => ['codigo_bs', 'descripcion', 'marca', 'modelo'],
                    'relations' => ['categoria']
                ],
                'profesores' => [
                    'model' => Profesor::class,
                    'fields' => ['nombre_usuario'],
                    'relations' => []
                ],
                // Aquí puedes añadir más tipos
            ];

            if (!isset($searchTypes[$type])) {
                return response()->json(['error' => 'Tipo de búsqueda no válido'], 400);
            }

            $config = $searchTypes[$type];
            $query = $config['model']::query();

            // Cargar relaciones si existen
            if (!empty($config['relations'])) {
                $query->with($config['relations']);
            }

            // Aplicar filtros de búsqueda
            $query->where(function($q) use ($config, $request) {
                foreach ($config['fields'] as $field) {
                    $q->orWhere($field, 'LIKE', '%' . $request->get('q') . '%');
                }
            });

            $results = $query->get();

            return response()->json([
                'success' => true,
                'results' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}