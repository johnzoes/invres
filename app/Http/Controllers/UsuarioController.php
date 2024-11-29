<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Usuario;
use App\Models\Profesor;
use App\Models\Asistente;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Salon; // No olvides agregar Salon si lo usas
use Spatie\Permission\Traits\HasRoles;


class UsuarioController extends Controller
{
    use HasRoles;
    // Mostrar lista de usuarios
    public function index()
    {
        if (Auth::check()) {
            Log::info('Roles del usuario: ' . json_encode(Auth::user()->getRoleNames()));
            Log::info('Permisos del usuario: ' . json_encode(Auth::user()->getAllPermissions()->pluck('name')));
        } else {
            Log::info('Usuario no autenticado');
        }

        $usuarios = Usuario::with('roles')->get(); // Cargar los roles de los usuarios
        return view('usuarios.index', compact('usuarios'));
    }

    public function show($id)
{
    // Buscar el usuario con sus relaciones
    $usuario = Usuario::with(['roles', 'profesor', 'asistente.salones'])->findOrFail($id);

    // Verifica el rol del usuario y muestra información según el caso
    return view('usuarios.show', compact('usuario'));
}
    

    // Mostrar el formulario de creación de usuarios
    public function create()
    {
        $salones = Salon::all(); // Cargar todos los salones para los asistentes
        $roles = Role::all(); // Cargar todos los roles disponibles
        return view('usuarios.create', compact('salones', 'roles'));
    }
    

    // Almacenar un nuevo usuario   
    public function store(Request $request)
    {
        // Validar los datos básicos del usuario
        $data = $request->validate([
            'nombre_usuario' => 'required|string|max:50|unique:usuarios',
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:6',
            'rol' => 'required|in:admin,profesor,asistente',
        ]);
    
        // Crear el usuario
        $usuario = Usuario::create([
            'nombre_usuario' => $data['nombre_usuario'],
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    
        // Asignar el rol al usuario
        $usuario->assignRole($data['rol']);
    
        // Si es profesor, crear la relación con la tabla profesores
        if ($data['rol'] === 'profesor') {
            Profesor::create(['id_usuario' => $usuario->id]);
        }
        // Si es asistente, crear la relación y asociar múltiples salones
        elseif ($data['rol'] === 'asistente') {
            $request->validate([
                'salones' => 'required|array', // Aseguramos que sea un array
                'salones.*' => 'exists:salones,id', // Validamos que cada salón exista
                'turno' => 'required|in:mañana,noche',
            ]);
    
            // Crear el asistente
            $asistente = Asistente::create([
                'id_usuario' => $usuario->id,
                'turno' => $request->turno,
            ]);
    
            // Asociar los salones al asistente
            $asistente->salones()->attach($request->salones);
        }
    
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado con éxito.');
    }
    

   // Mostrar el formulario de edición de roles y datos del usuario
   public function edit($id)
   {
       $usuario = Usuario::with('roles', 'asistente')->findOrFail($id);
       $roles = Role::all(); // Obtén todos los roles
       $salones = Salon::all(); // Obtén todos los salones disponibles
   
       return view('usuarios.edit', compact('usuario', 'roles', 'salones'));
   }
   

   public function update(Request $request, $id)
   {
       $usuario = Usuario::findOrFail($id);
   
       // Validar los datos
       $data = $request->validate([
           'nombre_usuario' => 'required|string|max:50|unique:usuarios,nombre_usuario,' . $id,
           'nombre' => 'required|string|max:50',
           'apellidos' => 'required|string|max:50',
           'email' => 'required|string|email|max:255|unique:usuarios,email,' . $id, // Asegurar que el email sea único excepto para este usuario
       ]);
   
       // Actualizar la información del usuario
       $usuario->update($data);
   
       return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado con éxito.');
   }
   

    // Eliminar un usuario
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);

        if ($usuario->hasRole('profesor')) {
            Profesor::where('id_usuario', $usuario->id)->delete();
        } elseif ($usuario->hasRole('asistente')) {
            Asistente::where('id_usuario', $usuario->id)->delete();
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

}
