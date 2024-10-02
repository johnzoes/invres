<?php

namespace App\Http\Controllers;

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
            'password' => 'required|string|min:6',
            'rol' => 'required|in:admin,profesor,asistente',
        ]);

        if (auth()->check()) {
            if (auth()->user()->hasRole('admin')) {
                dd('Usuario es admin');
            } else {
                dd('Usuario no es admin');
            }
        } else {
            dd('No hay un usuario autenticado');
        }
        

        // Crear el usuario y asignarle el rol
        $usuario = Usuario::create([
            'nombre_usuario' => $data['nombre_usuario'],
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'],
            'password' => Hash::make($data['password']), // Importar Hash
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

    // Mostrar el formulario de edición de roles
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $roles = Role::all(); // Cargar todos los roles disponibles
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    // Actualizar roles de un usuario
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        // Validar los roles seleccionados
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        // Asignar los roles seleccionados
        $usuario->syncRoles($request->roles);

        return redirect()->route('usuarios.index')->with('success', 'Roles actualizados con éxito.');
    }
}
