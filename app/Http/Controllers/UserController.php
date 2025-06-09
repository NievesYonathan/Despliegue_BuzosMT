<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\TipoDoc;
use App\Models\Estado;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Models\Seguridad;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    private $apiBase;

    public function __construct()
    {
        $this->apiBase = config('app.url') . '/api';
        Http::timeout(5);
    }

    public function index()
    {
        try {
            // $response = Http::get("{$this->apiBase}/usuarios");
            // $tiposResponse = Http::get("{$this->apiBase}/tipos-documentos");
            // $estadosResponse = Http::get("{$this->apiBase}/estados");

            // if (!$response->successful() || !$tiposResponse->successful() || !$estadosResponse->successful()) {
            //     throw new \Exception('Error al obtener datos de la API');
            // }

            // return view('Perfil-Admin-Usuarios.user-list', [
            //     'usuarios' => $response->json(),
            //     'tiposDocumentos' => $tiposResponse->json(),
            //     'estados' => $estadosResponse->json()
            // ]);
                    // Obtener todos los usuarios
            $usuarios = User::all();
            $tiposDocumentos = TipoDoc::all();
            $estados = Estado::all();

            return view('Perfil-Admin-Usuarios.user-list', compact('usuarios', 'tiposDocumentos', 'estados'));

        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión con el servidor');
        }
    }

    public function create()
    {
        try {
            // $response = Http::get("{$this->apiBase}/tipos-documentos");
            
            // if (!$response->successful()) {
            //     throw new \Exception('Error al obtener tipos de documentos');
            // }

            // return view('Perfil-Admin-Usuarios.user-new', ['tipos_documentos' => $response->json()]);

            $tipos_documentos = TipoDoc::all();
            return view('Perfil-Admin-Usuarios.user-new', compact('tipos_documentos'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión con el servidor');
        }
    }

    public function store(Request $request)
    {
        try {
            // $response = Http::post("{$this->apiBase}/usuarios", $request->all());

            // if ($response->successful()) {
            //     return redirect()->route('user-list')->with('alerta', 'Usuario creado con éxito');
            // }

            // $errorMessage = $response->json()['message'] ?? $response->json()['error'] ?? 'Error al crear usuario';
            // return back()->withErrors(['error' => $errorMessage])->withInput();

            // Validación de los datos
            $request->validate([
                'num_doc' => ['required', 'integer'], // Validar el número de documento
                't_doc' => ['required', 'integer'], // Validar tipo de documento
                'usu_nombres' => ['required', 'string', 'max:60'],
                'usu_apellidos' => ['required', 'string', 'max:45'],
                'email' => ['required', 'string', 'email', 'max:50', 'unique:usuarios'],
                'usu_fecha_nacimiento' => ['required', 'date'],
                'usu_sexo' => ['required', 'string', 'max:1'],
                'usu_telefono' => ['required', 'string', 'max:10'],
                'usu_direccion' => ['required', 'string', 'max:50'],
                'usu_estado' => ['required', 'integer'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Crear un nuevo usuario
            User::create([
                'num_doc' => $request->num_doc,
                't_doc' => $request->t_doc,
                'usu_nombres' => $request->usu_nombres,
                'usu_apellidos' => $request->usu_apellidos,
                'email' => $request->email,
                'usu_fecha_nacimiento' => $request->usu_fecha_nacimiento,
                'usu_sexo' => $request->usu_sexo,
                'usu_direccion' => $request->usu_direccion,
                'usu_telefono' => $request->usu_telefono,
                'usu_estado' => $request->usu_estado,
                'usu_fecha_contratacion' => now(), // Asignar la fecha de contratación actual
            ]);

            Seguridad::create([
                'usu_num_doc' => $request->num_doc,
                'seg_clave_hash' => Hash::make($request->password),
            ]);

            // Redirigir con mensaje de éxito
            return redirect()->route('user-list')->with('alerta', 'Usuario creado con éxito');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error de conexión con el servidor'])->withInput();
        }
    }

    public function update(Request $request, $num_doc)
    {
        try {
            // $response = Http::put("{$this->apiBase}/usuarios/{$num_doc}", $request->all());

            // if ($response->successful()) {
            //     return redirect()->route('user-list')->with('alerta', 'Usuario actualizado correctamente');
            // }

            // return back()->withErrors(['error' => $response->json()['message'] ?? 'Error al actualizar usuario']);
            
            $usuario = User::where('num_doc', $num_doc)->first();

            if (!$usuario) {
                return redirect()->route('user-list')->with('error', 'Usuario no encontrado.');
            }

            $telefono = $request->input('usuario_telefono', null);

            // Actualiza solo los campos que están presentes en el request
            $usuario->update([
                'usu_nombres' => $request->usu_nombres,
                'usu_apellidos' => $request->usu_apellidos,
                'email' => $request->email,
                'usu_telefono' => $request->usu_telefono,
                'usu_fecha_contratacion' => $request->usu_fecha_contratacion,  // Fecha de contratación
                'usu_estado' => $request->usu_estado,  // Estado
            ]);

            return redirect()->route('user-list')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error de conexión con el servidor']);
        }
    }

    public function cambiarestado($num_doc)
    {
        try {
            // $response = Http::put("{$this->apiBase}/usuarios/cambiar-estado/{$num_doc}");

            // if ($response->successful()) {
            //     return redirect()->route('user-list')->with('alerta', 'Estado actualizado correctamente');
            // }

            // return back()->withErrors(['error' => $response->json()['message'] ?? 'Error al cambiar estado']);

            // Buscar al usuario por su número de documento
            $usuario = User::where('num_doc', $num_doc)->first();

            if (!$usuario) {
                return redirect()->route('user-list')->with('error', 'Usuario no encontrado.');
            }

            // Cambiar el estado del usuario
            // Si el estado es "Activo" (1), lo cambiamos a "Inactivo" (0) y viceversa
            $nuevoEstado = $usuario->usu_estado == 1 ? 2 : 1;

            $usuario->usu_estado = $nuevoEstado;
            $usuario->save(); // Guardamos los cambios

            return redirect()->route('user-list')->with('success', 'Estado del usuario actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error de conexión con el servidor']);
        }
    }

    public function buscar(Request $request)
    {
        try {
            // $search = $request->input('search', '');
            
            // if ($search) {
            //     $response = Http::get("{$this->apiBase}/usuarios/buscar", ['search' => $search]);
            //     $resultado = $response->successful() ? collect($response->json()) : collect([]);
            // } else {
            //     $resultado = collect([]);
            // }

            // return view('Perfil-Admin-Usuarios.user-search', compact('search', 'resultado'));

            $search = $request->input('search', '');  // Obtener el término de búsqueda
            $resultado = null;

            if ($search) {
                // Buscar los usuarios que coinciden con el término de búsqueda
                $resultado = User::where('usu_nombres', 'LIKE', "%$search%")
                    ->orWhere('usu_apellidos', 'LIKE', "%$search%")
                    ->orWhere('num_doc', 'LIKE', "%$search%")
                    ->get();

                // Si no se encuentran resultados
                if ($resultado->isEmpty()) {
                    Session::flash('alerta', "No se encontraron resultados para '$search'.");
                } else {
                    Session::flash('alerta', "Búsqueda realizada para '$search'.");
                }
            } else {
                Session::flash('alerta', "Por favor, ingrese un término de búsqueda.");
            }

            // Retornar la vista con los resultados
            return view('Perfil-Admin-Usuarios.user-search', compact('search', 'resultado'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error de conexión con el servidor']);
        }
    }
}
