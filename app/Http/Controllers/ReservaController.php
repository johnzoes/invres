<?php

namespace App\Http\Controllers;
use App\Models\Notificacion;
use App\Models\HistorialEstadoItem;
use App\Notifications\NotificacionReserva;
use App\Models\Reserva;
use App\Models\DetalleReservaItem;
use App\Models\Asistente;
use App\Models\Profesor;
use App\Models\Categoria;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\UnidadDidactica;
use App\Models\Item;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Events\NotificationEvent;


class ReservaController extends Controller
{

    // Método para listar todas las reservas
    public function index()
    {
        $user = auth()->user();
        $query = Reserva::with(['profesor.usuario', 'unidadDidactica', 'detalles.historialEstados']);
    
        // Filtrar según el rol
        if ($user->hasRole('profesor')) {
            $query->whereHas('profesor.usuario', function ($q) use ($user) {
                $q->where('id', $user->id);
            });
        } elseif ($user->hasRole('asistente')) {
            $salonIds = $user->asistente->salones->pluck('id');
            $query->whereHas('detalles.item.armario.salon', function ($q) use ($salonIds) {
                $q->whereIn('id_salon', $salonIds);
            });
        }
    
        $reservas = $query->latest()->get();
    
        // Obtener estadísticas basadas en los estados de los detalles
        $stats = [
            'total' => $reservas->count(),
            'pendientes' => $reservas->filter(function($reserva) {
                return $reserva->detalles->contains(function($detalle) {
                    return $detalle->estado === 'pendiente';
                });
            })->count(),
            'aceptados' => $reservas->filter(function($reserva) {
                return $reserva->detalles->contains(function($detalle) {
                    return $detalle->estado === 'aceptado';
                });
            })->count(),
            'rechazados' => $reservas->filter(function($reserva) {
                return $reserva->detalles->contains(function($detalle) {
                    return $detalle->estado === 'rechazado';
                });
            })->count()
        ];
    
        return view('reservas.index', compact('reservas', 'stats'));
    }
    
    

    // Mostrar el formulario para crear una nueva reserva
    public function create()
    {
        $categorias = Categoria::with(['items' => function($query) {
            $query->where('estado', 'like', 'disponible%')
                  ->select('id', 'descripcion', 'cantidad', 'tipo', 'estado', 'marca', 'modelo', 'id_categoria');
        }])->get();
    
        return view('reservas.create', compact('categorias'));
    }
    

    public function createtwo(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:categorias,id',
            'cantidad' => 'required|array',
            'cantidad.*' => 'required|integer|min:1',
        ]);
    
        // Crear array de items seleccionados
        $itemsSeleccionados = [];
        foreach ($request->items as $categoriaId) {
            $itemsSeleccionados[$categoriaId] = $request->input('cantidad.' . $categoriaId);
        }
    
        // Almacenar en la sesión
        session(['items' => $itemsSeleccionados]);
    
        // Cargar las unidades didácticas
        $unidades_didacticas = UnidadDidactica::all();
    
        return view('reservas.create-two', compact('unidades_didacticas'));
    }




    public function store(Request $request)
    {
        \Log::info('Iniciando proceso de reserva', [
            'session_items' => session('items'),
            'request_data' => $request->all()
        ]);
    
        try {
            $request->validate([
                'id_unidad_didactica' => 'required|exists:unidades_didacticas,id',
                'turno' => 'required|in:mañana,noche',
            ]);
    
            $itemsSeleccionados = session('items');
            \Log::info('Items seleccionados', ['items' => $itemsSeleccionados]);
    
            if (!$itemsSeleccionados) {
                return back()->withInput()->with('error', 'No se encontraron items seleccionados.');
            }
    
            $profesor = auth()->user()->profesor;
            if (!$profesor) {
                return back()->withInput()->with('error', 'No tienes un profesor asociado.');
            }
    
            DB::beginTransaction();
    
            $reserva = Reserva::create([
                'fecha_prestamo' => now(),
                'id_profesor' => $profesor->id,
                'id_unidad_didactica' => $request->id_unidad_didactica,
                'turno' => $request->turno,
            ]);
    
            $asistentesNotificados = [];
    
            foreach ($itemsSeleccionados as $categoriaId => $cantidadSolicitada) {
                // Primero buscar items tipo paquete
                $itemsPaquete = Item::where('id_categoria', $categoriaId)
                    ->where('estado', 'disponible')
                    ->where('tipo', 'paquete')
                    ->where('cantidad', '>', 0)
                    ->orderBy('cantidad', 'desc')
                    ->get();
    
                // Si no hay suficientes paquetes, buscar unidades individuales
                $itemsUnidad = Item::where('id_categoria', $categoriaId)
                    ->where('estado', 'disponible')
                    ->where('tipo', 'unidad')
                    ->where('cantidad', '>', 0)
                    ->get();
    
                \Log::info('Items encontrados', [
                    'categoria_id' => $categoriaId,
                    'paquetes_count' => $itemsPaquete->count(),
                    'unidades_count' => $itemsUnidad->count()
                ]);
    
                $cantidadRestante = $cantidadSolicitada;
                $itemsAProcesar = collect();
    
                // Primero intentar con paquetes
                foreach ($itemsPaquete as $item) {
                    if ($cantidadRestante <= 0) break;
                    $cantidadAReservar = min($cantidadRestante, $item->cantidad);
                    $itemsAProcesar->push([
                        'item' => $item,
                        'cantidad' => $cantidadAReservar
                    ]);
                    $cantidadRestante -= $cantidadAReservar;
                }
    
                // Si aún falta cantidad, usar unidades individuales
                if ($cantidadRestante > 0) {
                    foreach ($itemsUnidad as $item) {
                        if ($cantidadRestante <= 0) break;
                        $itemsAProcesar->push([
                            'item' => $item,
                            'cantidad' => 1  // Para unidades siempre es 1
                        ]);
                        $cantidadRestante -= 1;
                    }
                }
    
                // Verificar si tenemos suficientes items
                if ($cantidadRestante > 0) {
                    throw new \Exception("No hay suficientes items disponibles en la categoría. Faltan {$cantidadRestante} unidades.");
                }
    
                // Procesar los items seleccionados
                foreach ($itemsAProcesar as $itemData) {
                    $item = $itemData['item'];
                    $cantidadAReservar = $itemData['cantidad'];
    
                    $detalleReserva = DetalleReservaItem::create([
                        'id_reserva' => $reserva->id,
                        'id_item' => $item->id,
                        'fecha_reserva' => now(),
                        'hora_reserva' => now(),
                        'cantidad_reservada' => $cantidadAReservar,
                        'estado' => 'pendiente',
                    ]);
    
                    // Notificar al asistente correspondiente
                    if ($item->armario && $item->armario->salon) {
                        $asistente = Asistente::whereHas('salones', function($query) use ($item) {
                            $query->where('salones.id', $item->armario->salon->id);
                        })
                        ->where('turno', $request->turno)
                        ->with('usuario')
                        ->first();
    
                        if ($asistente && !in_array($asistente->id, $asistentesNotificados)) {
                            if ($asistente->usuario) {
                                $asistente->usuario->notify(new NotificacionReserva([
                                    'mensaje' => "Nueva reserva de items en el salón {$item->armario->salon->nombre_salon}",
                                    'reserva_id' => $reserva->id,
                                    'usuario_remitente' => auth()->user()->nombre,
                                    'usuario_destinatario' => $asistente->usuario->nombre,
                                    'turno' => $request->turno,
                                ]));
    
                                $asistentesNotificados[] = $asistente->id;
                                event(new NotificationEvent($reserva));
                            }
                        }
                    }
                }
            }
    
            DB::commit();
            session()->forget('items');
    
            return redirect()
                ->route('reservas.index')
                ->with('success', 'Reserva creada exitosamente');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en proceso de reserva', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }


    
    private function notificarAsistente($item, $reserva, $turno, &$asistentesNotificados)
    {
        if ($item->armario && $item->armario->salon) {
            $salonId = $item->armario->salon->id;
            $asistente = Asistente::whereHas('salones', function ($query) use ($salonId) {
                $query->where('salones.id', $salonId);
            })->where('turno', $turno)->first();
    
            if ($asistente && !in_array($asistente->id, $asistentesNotificados) && $asistente->usuario) {
                $asistente->usuario->notify(new NotificacionReserva([
                    'mensaje' => "Nueva reserva de items en el salón {$item->armario->salon->nombre_salon}",
                    'reserva_id' => $reserva->id,
                    'usuario_remitente' => auth()->user()->nombre,
                    'usuario_destinatario' => $asistente->usuario->nombre,
                    'turno' => $turno,
                ]));
    
                $asistentesNotificados[] = $asistente->id;
                event(new NotificationEvent($reserva));
            }
        }
    }
    
    
    public function show($id)
    {
        try {
            $user = auth()->user();
            
            // Para asistentes, verificar primero si tiene items en la reserva
            if ($user->hasRole('asistente')) {
                // Verificar si la reserva tiene items del asistente
                $tieneItemsAsignados = DetalleReservaItem::where('id_reserva', $id)
                    ->whereHas('item.armario.salon.asistentes', function ($query) use ($user) {
                        $query->where('asistentes.id', $user->asistente->id);
                    })
                    ->whereHas('reserva', function ($query) use ($user) {
                        $query->where('turno', $user->asistente->turno);
                    })
                    ->exists();
    
                if (!$tieneItemsAsignados) {
                    return redirect()->route('reservas.index')
                        ->with('info', 'No tienes items asignados en esta reserva.');
                }
            }
    
            // Cargar la reserva con sus relaciones
            $reserva = Reserva::with([
                'detalles.item.armario.salon',
                'profesor.usuario',
                'unidadDidactica',
                'detalles.historialEstados'
            ])->findOrFail($id);
    
            $detallesFiltrados = collect();
            $puedeAprobar = false;
    
            if ($user->hasRole('admin')) {
                // Administrador ve todos los detalles
                $detallesFiltrados = $reserva->detalles;
                $puedeAprobar = true;
            } 
            elseif ($user->hasRole('profesor')) {
                // Profesor ve solo sus propias reservas
                if ($reserva->profesor->usuario->id === $user->id) {
                    $detallesFiltrados = $reserva->detalles;
                    $puedeAprobar = false;
                } else {
                    return redirect()->route('reservas.index')
                        ->with('error', 'No tienes permiso para ver esta reserva.');
                }
            }
            elseif ($user->hasRole('asistente')) {
                // Asistente solo ve los items de sus salones y turno
                $salonesAsignadosIds = $user->asistente->salones->pluck('id')->toArray();
                
                $detallesFiltrados = $reserva->detalles->filter(function ($detalle) use ($user, $reserva, $salonesAsignadosIds) {
                    $salonId = $detalle->item->armario->salon->id ?? null;
                    return $salonId 
                        && in_array($salonId, $salonesAsignadosIds)
                        && $user->asistente->turno === $reserva->turno;
                });
                
                $puedeAprobar = true;
            }
    
            // Agrupar detalles por estado para estadísticas
            $estadisticas = [
                'total' => $detallesFiltrados->count(),
                'pendientes' => $detallesFiltrados->where('estado', 'pendiente')->count(),
                'aceptados' => $detallesFiltrados->where('estado', 'aceptado')->count(),
                'prestados' => $detallesFiltrados->where('estado', 'prestado')->count(),
                'devueltos' => $detallesFiltrados->where('estado', 'devuelto')->count(),
                'rechazados' => $detallesFiltrados->where('estado', 'rechazado')->count()
            ];
    
            // Agrupar por salón para mejor organización
            $detallesPorSalon = $detallesFiltrados->groupBy(function ($detalle) {
                return $detalle->item->armario->salon->nombre_salon ?? 'Sin salón';
            });
    
            return view('reservas.show', compact(
                'reserva',
                'detallesFiltrados',
                'detallesPorSalon',
                'estadisticas',
                'puedeAprobar'
            ));
    
        } catch (\Exception $e) {
            \Log::error('Error al mostrar reserva', [
                'reserva_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('reservas.index')
                ->with('error', 'Error al cargar la reserva.');
        }
    }
    

    // Eliminar una reserva
    public function destroy($id)
    {
        // Busca la reserva
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return redirect()->route('reservas.index')->with('error', 'Reserva no encontrada.');
        }

        // Elimina la reserva
        $reserva->delete();

        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada con éxito.');
    }




    public function approve($detalleId)
    {
        try {
            // Cargar el detalle con todas las relaciones necesarias
            $detalle = DetalleReservaItem::with([
                'item.armario.salon',
                'reserva.profesor.usuario'
            ])->findOrFail($detalleId);
    
            $user = auth()->user();
            
            // Si no es admin o asistente, no puede aprobar
            if (!$user->hasRole(['admin', 'asistente'])) {
                \Log::warning('Usuario sin rol apropiado intentando aprobar', [
                    'user_id' => $user->id,
                    'roles' => $user->roles->pluck('name')
                ]);
                abort(403, 'No tienes permiso para aprobar reservas.');
            }
    
            // Si es asistente, verificar que el item pertenezca a sus salones y turno
            if ($user->hasRole('asistente')) {
                // Obtener el salón del item
                $salonDelItem = $detalle->item->armario->salon ?? null;
                
                if (!$salonDelItem) {
                    \Log::error('Item sin salón asignado', [
                        'detalle_id' => $detalleId,
                        'item_id' => $detalle->item->id
                    ]);
                    return back()->with('error', 'El item no tiene un salón asignado.');
                }
    
                // Verificar si el asistente tiene asignado este salón específico
                $tieneAccesoASalon = $user->asistente
                    ->salones()
                    ->where('salones.id', $salonDelItem->id)
                    ->exists();
    
                // Verificar que sea el turno correcto
                $esTurnoCorrespondiente = $user->asistente->turno === $detalle->reserva->turno;
    
                \Log::info('Verificación de permisos asistente', [
                    'asistente_id' => $user->asistente->id,
                    'salon_item' => $salonDelItem->id,
                    'tiene_acceso' => $tieneAccesoASalon,
                    'turno_asistente' => $user->asistente->turno,
                    'turno_reserva' => $detalle->reserva->turno,
                    'es_turno_correcto' => $esTurnoCorrespondiente
                ]);
    
                if (!$tieneAccesoASalon || !$esTurnoCorrespondiente) {
                    return back()->with('error', 
                        'Solo puedes aprobar items de tus salones asignados y en tu turno correspondiente.');
                }
            }
    
            // Verificar que el estado sea pendiente
            if ($detalle->estado !== 'pendiente') {
                return back()->with('error', 'Este item ya no está pendiente de aprobación.');
            }
    
            DB::beginTransaction();
    
            // Registrar en historial
            HistorialEstadoItem::create([
                'id_detalle_reserva_item' => $detalle->id,
                'estado' => 'aceptado',
                'fecha_estado' => now(),
                'usuario_id' => $user->id  // Registrar quién aprobó
            ]);
    
            // Actualizar inventario
            $item = $detalle->item;
            if ($item) {
                // Verificar que haya suficiente cantidad disponible
                if ($item->cantidad < $detalle->cantidad_reservada) {
                    DB::rollBack();
                    return back()->with('error', 'No hay suficiente cantidad disponible del item.');
                }
    
                $item->cantidad -= $detalle->cantidad_reservada;
                $item->actualizarEstado();
                $item->save();
            }
    
            // Actualizar estado del detalle
            $detalle->update([
                'estado' => 'aceptado',
                'aprobado_por' => $user->id  // Opcional: si tienes esta columna
            ]);
    
            // Notificar al profesor
            if ($detalle->reserva->profesor->usuario) {
                $usuarioProfesor = $detalle->reserva->profesor->usuario;
                $usuarioProfesor->notify(new NotificacionReserva([
                    'mensaje' => "Item '{$item->descripcion}' de tu reserva ha sido aprobado",
                    'reserva_id' => $detalle->reserva->id,
                    'usuario_remitente' => $user->nombre,
                    'usuario_destinatario' => $usuarioProfesor->nombre,
                    'detalle' => "Cantidad aprobada: {$detalle->cantidad_reservada}"
                ]));
            }
    
            DB::commit();
    
            \Log::info('Aprobación exitosa', [
                'detalle_id' => $detalleId,
                'aprobador_id' => $user->id,
                'salon_id' => $salonDelItem->id ?? null
            ]);
    
            return redirect()
                ->route('reservas.show', $detalle->id_reserva)
                ->with('success', 'Item aprobado exitosamente.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en aprobación', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error al procesar la aprobación: ' . $e->getMessage());
        }
    }
    



// Rechazar el ítem específico de la reserva
        public function reject(Request $request, $detalleId)
        {
            // Encontrar el detalle de la reserva específico por su ID
            $detalle = DetalleReservaItem::findOrFail($detalleId);

            // Registrar el cambio en HistorialEstadoItem con motivo de rechazo
            HistorialEstadoItem::create([
                'id_detalle_reserva_item' => $detalle->id,
                'estado' => 'rechazado',
                'motivo_rechazo' => $request->motivo_rechazo,
                'fecha_estado' => now(),
            ]);

            // Actualizar el estado del detalle de la reserva
            $detalle->update(['estado' => 'rechazado']);

            return redirect()->route('reservas.show', $detalle->id_reserva)->with('error', 'Ítem rechazado.');
        }

        // Prestar físicamente el ítem específico de la reserva
        public function lend($detalleId)
        {
            $detalle = DetalleReservaItem::findOrFail($detalleId);

            if ($detalle->estado == 'aceptado') {
                $detalle->update(['estado' => 'prestado']);

                // Registrar el cambio en HistorialEstadoItem
                HistorialEstadoItem::create([
                    'id_detalle_reserva_item' => $detalle->id,
                    'estado' => 'prestado',
                    'fecha_estado' => now(),
                ]);

                return redirect()->back()->with('success', 'Ítem prestado físicamente.');
            }

            return redirect()->back()->with('error', 'No se puede prestar este ítem.');
        }

        // Registrar devolución del ítem específico de la reserva
        public function return($detalleId)
        {
            $detalle = DetalleReservaItem::findOrFail($detalleId);

            if ($detalle->estado == 'prestado') {
                $detalle->update(['estado' => 'devuelto']);

                // Registrar el cambio en HistorialEstadoItem
                HistorialEstadoItem::create([
                    'id_detalle_reserva_item' => $detalle->id,
                    'estado' => 'devuelto',
                    'fecha_estado' => now(),
                ]);

                        // Aumentar la cantidad en el inventario del ítem y actualizar su estado
                $item = $detalle->item;
                if ($item) {
                    $item->cantidad += $detalle->cantidad_reservada;
                    $item->actualizarEstado(); // Actualiza el estado del ítem ('disponible' si hay stock)
                    $item->save();
                }

                return redirect()->back()->with('success', 'Ítem devuelto.');
            }

            return redirect()->back()->with('error', 'No se puede devolver este ítem.');
        }



        public function generarPDF($id)
        {
            // Cargar la reserva con sus relaciones
            $reserva = Reserva::with('profesor.usuario', 'unidadDidactica', 'detalles.item')->findOrFail($id);
        
            // Obtener el profesor desde la relación
            $profesor = $reserva->profesor;
        
            // Verificar si la reserva tiene un profesor asociado
            if (!$profesor) {
                return redirect()->back()->with('error', 'No se pudo encontrar el profesor asociado a esta reserva.');
            }
        
            // Cargar los detalles de la reserva
            $detalles = $reserva->detalles;
        
            // Generar el PDF con la vista y pasar las variables necesarias
            $pdf = Pdf::loadView('reservas.solicitud_pdf', compact('reserva', 'detalles', 'profesor'));
        
            // Descargar el PDF
            return $pdf->download('solicitud_reserva_' . $reserva->id . '.pdf');
        }


        public function obtenerReservas($profesorId = null)
        {
            // Consulta base para obtener todas las reservas con sus relaciones
            $queryBase = Reserva::with(['unidadDidactica', 'detalles.item', 'profesor']);
        
            // Si se proporciona un ID de profesor, filtramos por él
            if ($profesorId) {
                $queryBase->where('id_profesor', $profesorId);
            }
        
            // Agrupamos las reservas por estado en una sola consulta
            $reservas = $queryBase->whereHas('detalles', function ($query) {
                $query->whereIn('estado', ['pendiente', 'aceptado', 'prestado', 'devuelto']);
            })->get();
        
            // Inicializamos un arreglo para clasificar las reservas por estado
            $resultados = [
                'reservasPendientes' => [],
                'reservasAprobadas' => [],
                'reservasPrestadas' => [],
                'reservasDevueltas' => []
            ];
        
            // Clasificamos las reservas según su estado
            foreach ($reservas as $reserva) {
                foreach ($reserva->detalles as $detalle) {
                    switch ($detalle->estado) {
                        case 'pendiente':
                            $resultados['reservasPendientes'][] = $reserva;
                            break;
                        case 'aceptado':
                            $resultados['reservasAprobadas'][] = $reserva;
                            break;
                        case 'prestado':
                            $resultados['reservasPrestadas'][] = $reserva;
                            break;
                        case 'devuelto':
                            $resultados['reservasDevueltas'][] = $reserva;
                            break;
                    }
                }
            }
        
            return $resultados;
        }
        



}
