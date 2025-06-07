<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Cargo;

class CargoController extends Controller
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
            // $response = Http::get("{$this->apiBase}/cargos");

            // if (!$response->successful()) {
            //     throw new \Exception('Error al obtener los cargos');
            // }

            // return view('Perfil-Admin-Usuarios.cargos', [
            //     'cargos' => $response->json()
            // ]);

            $cargos = Cargo::with('usuarios')->paginate(10);
            return view('Perfil-Admin-Usuarios.cargos', compact('cargos'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión con el servidor');
        }
    }

    public function create()
    {
        return view('Perfil-Admin-Usuarios.cargos-new'); // Crea esta vista si no existe
    }

    public function store(Request $request)
    {
        // Validar entrada
        $request->validate([
            'car_nombre' => 'required|string|max:255',
        ]);

        try {
            Cargo::create([
                'car_nombre' => $request->car_nombre,
            ]);

            return redirect()->route('cargos')->with('success', 'Cargo creado correctamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error de conexión con el servidor'])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        // Validar entrada
        $request->validate([
            'car_nombre' => 'required|string|max:255',
        ]);

        try {
            // Buscar el cargo
            $cargo = Cargo::findOrFail($id);

            // Actualizar el cargo
            $cargo->update([
                'car_nombre' => $request->car_nombre,
            ]);

            // Redireccionar con mensaje
            return redirect()->route('cargos')->with('success', 'Cargo actualizado correctamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar el cargo']);
        }
    }

    public function destroy($id)
    {
        try {
            $cargo = Cargo::findOrFail($id);
                
            // Verificar si el cargo está asignado a usuarios
            if ($cargo->usuarios()->exists()) {
                return back()->withErrors(['error' => 'No se puede eliminar el cargo porque está asignado a usuarios.']);
            }
            
            $cargo->delete();

            return redirect()->route('cargos')->with('success', 'Cargo eliminado correctamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'No se pudo eliminar el cargo.']);
        }
    }

}
