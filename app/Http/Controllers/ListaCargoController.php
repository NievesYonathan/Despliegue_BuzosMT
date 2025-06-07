<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\User; // Modelo de Usuario
use App\Models\Cargo;   // Modelo de Cargo


class ListaCargoController extends Controller
{
    private $apiBase;

    public function __construct()
    {
        $this->apiBase = env('APP_URL') . '/api';
        Http::timeout(5);
    }

    public function index()
    {
        try {
            // $usuariosResponse = Http::get("{$this->apiBase}/usuarios-cargos");
            // $cargosResponse = Http::get("{$this->apiBase}/cargos");

            // if (!$usuariosResponse->successful() || !$cargosResponse->successful()) {
            //     throw new \Exception('Error al obtener los datos');
            // }

            // // Obtener estructura paginada desde la respuesta JSON
            // $data = $usuariosResponse->json();

            // $usuarios = new LengthAwarePaginator(
            //     collect($data['data'])->map(function ($usuario) {
            //         // Convertir relaciones internas a objetos para Blade
            //         $usuario = (object) $usuario;

            //         // Asegurarte de que tipo_documento sea un objeto
            //         $usuario->tipo_documento = isset($usuario->tipo_documento) ? (object) $usuario->tipo_documento : null;
            //         $usuario->cargos = isset($usuario->cargos) ? collect($usuario->cargos)->map(fn($c) => (object) $c) : collect([]);

            //         return $usuario;
            //     }),
            //     $data['total'],
            //     $data['per_page'],
            //     $data['current_page'],
            //     ['path' => request()->url(), 'query' => request()->query()]
            // );

            // return view('Perfil-Admin-Usuarios.user-list-cargo', [
            //     'usuarios' => $usuarios,
            //     'cargos' => collect($cargosResponse->json()),
            // ]);

            // Obtener todos los usuarios con sus cargos relacionados
            $usuarios = User::with('cargos', 'tipoDocumento')->paginate(10);

            // Obtener todos los cargos disponibles
            $cargos = Cargo::all();

            // Retornar la vista con los datos
            return view('Perfil-Admin-Usuarios.user-list-cargo', compact('usuarios', 'cargos'));

        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión con el servidor');
        }
    }


    public function store(Request $request)
    {
        // Validaciones (igual)
        $request->validate([
            'numDoc' => 'required|exists:usuarios,num_doc',
            'idCargo' => 'required|array|size:1',
            'idCargo' => 'exists:cargos,id_cargos',
        ]);

        $usuario = User::where('num_doc', $request->numDoc)->firstOrFail();

        $cargoConDatos = [
            $request->idCargo => [
                'fecha_asignacion' => now(),
                'estado_asignacion' => 1,
            ],
        ];

        $usuario->cargos()->sync($cargoConDatos);

        return redirect()->route('user-list-cargo')->with('success', 'Cargo asignado correctamente.');
    }
}
