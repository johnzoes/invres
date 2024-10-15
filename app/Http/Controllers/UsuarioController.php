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
    // Validar los datos comunes para todos los usuarios
    $data = $request->validate([
        'nombre_usuario' => 'required|string|max:50|unique:usuarios',
        'nombre' => 'required|string|max:50',
        'apellidos' => 'required|string|max:50',
        'email' => 'required|string|email|max:255|unique:usuarios', // Añadir validación de email
        'password' => 'required|string|min:6',
        'rol' => 'required|in:admin,profesor,asistente',
    ]);

    // Crear el usuario y asignarle el rol
    $usuario = Usuario::create([
        'nombre_usuario' => $data['nombre_usuario'],
        'nombre' => $data['nombre'],
        'apellidos' => $data['apellidos'],
        'email' => $data['email'], // Guardar el email
        'password' => Hash::make($data['password']), // Hash de la contraseña
    ]);

    // Asignar el rol seleccionado
    $usuario->assignRole($data['rol']);

    // Si el rol es profesor, crear un registro en la tabla profesores
    if ($data['rol'] === 'profesor') {
        Profesor::create([
            'id_usuario' => $usuario->id,
        ]);
    }

    // Si el rol es asistente, crear un registro en la tabla asistentes
    if ($data['rol'] === 'asistente') {
        $request->validate([
            'id_salon' => 'required|exists:salones,id',
            'turno' => 'required|in:mañana,noche',
        ]);

        Asistente::create([
            'id_usuario' => $usuario->id,
            'id_salon' => $request->id_salon,
            'turno' => $request->turno,
        ]);
    }

    return redirect()->route('usuarios.index')->with('success', 'Usuario creado con éxito.');
}

   // Mostrar el formulario de edición de roles y datos del usuario
public function edit($id)
{
    $usuario = Usuario::findOrFail($id);
    $roles = Role::all(); // Cargar todos los roles disponibles
    return view('usuarios.edit', compact('usuario', 'roles'));
}

// Actualizar los roles y la información del usuario
public function update(Request $request, $id)
{
    $usuario = Usuario::findOrFail($id);

    // Validar los datos incluyendo el email
    $data = $request->validate([
        'nombre_usuario' => 'required|string|max:50|unique:usuarios,nombre_usuario,' . $id,
        'nombre' => 'required|string|max:50',
        'apellidos' => 'required|string|max:50',
        'email' => 'required|string|email|max:255|unique:usuarios,email,' . $id, // Asegurar que el email sea único excepto para el usuario actual
        'password' => 'nullable|string|min:6',
        'roles' => 'required|array',
        'roles.*' => 'exists:roles,name',
    ]);

    // Actualizar la información del usuario
    $usuario->update([
        'nombre_usuario' => $data['nombre_usuario'],
        'nombre' => $data['nombre'],
        'apellidos' => $data['apellidos'],
        'email' => $data['email'], // Actualizar el email
        'password' => $data['password'] ? Hash::make($data['password']) : $usuario->password, // Solo actualizar la contraseña si se proporciona
    ]);

    // Actualizar los roles seleccionados
    $usuario->syncRoles($data['roles']);

    return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado con éxito.');
}

public function destroy($id)
{
    // Busca el usuario por su ID
    $usuario = Usuario::findOrFail($id);

    // Elimina el usuario
    $usuario->delete();

    // Redirige a la lista de usuarios con un mensaje de éxito
    return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
}

}
