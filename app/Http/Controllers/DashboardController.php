<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ReservaController;
use App\Models\DetalleReservaItem;

use App\Models\Reserva;
use App\Models\Profesor;

use App\Models\Categoria;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $reservaController;

    public function __construct(ReservaController $reservaController)
    {
        $this->reservaController = $reservaController;
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    
        $usuario = Auth::user();
    
        if ($usuario->hasRole('admin')) {
            // Obtener mes actual
            $mesActual = now()->format('m');
        
            // Estadísticas de reservas por categoría para el mes actual
            $categorias = Categoria::withCount(['items as total_reservas' => function ($query) use ($mesActual) {
                $query->whereHas('detallesReserva', function ($subQuery) use ($mesActual) {
                    $subQuery->whereHas('reserva', function ($reservaQuery) use ($mesActual) {
                        $reservaQuery->whereMonth('created_at', $mesActual);
                    });
                });
            }])->orderByDesc('total_reservas')->get();
        
            // Top 10 profesores con más reservas en el mes actual
            $profesoresTop = Profesor::with('usuario') // Carga el usuario relacionado
            ->withCount(['reservas as reservas_count' => function ($query) use ($mesActual) {
                $query->whereMonth('created_at', $mesActual);
            }])
            ->orderBy('reservas_count', 'desc')
            ->take(10)
            ->get();    
        

                // Obtener reservas para la tabla
             $reservas = Reserva::with(['detalles', 'profesor.usuario', 'unidadDidactica'])
                    ->orderBy('created_at', 'desc')
                    ->get();
        
            return view('dashboard.admin', [
                'categorias' => $categorias,      // Datos para el gráfico de categorías
                'profesoresTop' => $profesoresTop, // Datos para el gráfico de profesores
                'reservas' => $reservas,          // Datos para la tabla de reservas
            ]);
        }
        
        
        elseif ($usuario->hasRole('asistente')) {
            
            // Obtener el asistente relacionado al usuario autenticado
            $asistente = $usuario->asistente;

            if (!$asistente) {
                return redirect()->back()->withErrors('No tienes un perfil de asistente asociado.');
            }

            // Obtener los salones relacionados al asistente
            $salonIds = $asistente->salones->pluck('id')->toArray();

            // Obtener los detalles de las reservas que correspondan a los salones y turnos del asistente
            $detalles = DetalleReservaItem::whereHas('item.armario.salon', function ($query) use ($salonIds) {
                    $query->whereIn('id', $salonIds); // Filtrar por los salones asignados al asistente
                })
                ->whereHas('reserva', function ($query) use ($asistente) {
                    $query->where('turno', $asistente->turno); // Filtrar por el turno del asistente
                })
                ->with(['reserva.unidadDidactica', 'item', 'reserva.profesor.usuario'])
                ->get();

            // Reconstruir las reservas desde los detalles filtrados
            $reservas = $detalles->groupBy('id_reserva')->map(function ($detalles) {
                $reserva = $detalles->first()->reserva;
                $reserva->detalles = $detalles; // Asignar solo los detalles filtrados
                return $reserva;
            })->values();

            // Ordenar las reservas por fecha de creación de forma descendente
            $reservas = $reservas->sortByDesc(function ($reserva) {
                return $reserva->created_at;
            });

            // Retornar la vista del asistente con las reservas filtradas
            return view('dashboard.asistente', ['reservas' => $reservas]);

        }
        


        elseif ($usuario->hasRole('profesor')) {
            $profesor = $usuario->profesor;
    
            if (!$profesor) {
                return redirect()->route('dashboard')->withErrors('No tienes un perfil de profesor asociado.');
            }
    
            $reservas = $this->reservaController->obtenerReservas($profesor->id);
    
            return view('dashboard.profesor', $reservas);
        }
    
        return redirect()->route('login')->withErrors('No tienes un rol válido.');
    }
}
