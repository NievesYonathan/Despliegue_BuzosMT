<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\User;
use App\Models\MateriaPrima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Importar Http para consumir la API

class MateriaPrimaController extends Controller
{
    public function index()
    {
        $materiaPrima = []; // Inicializar la variable para evitar errores

        try {
            // Llamar a la API para obtener los datos de materia prima
            // $response = Http::get(config('app.url') . '/api/materia-prima');


            // if ($response->successful()) {
            //     $materiaPrima = $response->json(); // Convertir la respuesta JSON en un array
            // }
            $materiaPrima = MateriaPrima::all();
            return view("Perfil_Inventario.item-list", compact("materiaPrima"));

        } catch (\Exception $e) {
            // Manejar errores sin romper la vista
        }

    }

    public function show($id)
    {
        return view("Perfil_Inventario.item-detail", compact("id"));
    }

    public function showSearchForm()
    {
        return view('Perfil_Inventario.search-item');
    }

    public function search(Request $request)
    {
        $busqueda = $request->input('busqueda');

        $materiaPrima = MateriaPrima::where('mat_pri_nombre', 'LIKE', '%' . $busqueda . '%')->get();

        return view('Perfil_Inventario.search-item-results', [
            'busqueda' => $busqueda,
            'materiaPrima' => $materiaPrima
        ]);
    }

    public function form_nuevo()
    {
        $estados = Estado::all();
        $proveedores = User::whereHas('cargos', function ($query) {
            $query->where('id_cargos', 5);
        })->get();

        return view("Perfil_Inventario.new-item", compact("estados", "proveedores"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:140',
            'descripcion' => 'nullable|string|max:255',
            'unidad_medida' => 'required|string|max:20',
            'cantidad' => 'required|integer|min:1',
            'estado' => 'required|boolean',
            'fecha_compra' => 'required|date',
            'proveedor_id' => 'required|integer',
        ]);

        try {
            MateriaPrima::create([
                'mat_pri_nombre' => $request->nombre,
                'mat_pri_descripcion' => $request->descripcion,
                'mat_pri_unidad_medida' => $request->unidad_medida,
                'mat_pri_cantidad' => $request->cantidad,
                'mat_pri_estado' => $request->estado,
                'fecha_compra_mp' => $request->fecha_compra,
                'proveedores_id_proveedores' => $request->proveedor_id,
            ]);

            return redirect()->route('lista-item')->with('success', 'Materia prima creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Excepción: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $materiaPrima = MateriaPrima::findOrFail($id);
        $estados = Estado::all();
        $proveedores = User::whereHas('cargos', function ($query) {
            $query->where('id_cargos', 5);
        })->get();
    
        // Devolver la vista explícitamente
        return response()->view("Perfil_Inventario.update-item", compact("materiaPrima", "estados", "proveedores"));
    }

    public function delete($id)
    {
        try {
            // Enviar solicitud DELETE a la API
            $response = Http::delete(config('app.url') . "/api/materia-prima/{$id}");

            if ($response->successful()) {
                return redirect()->route('lista-item')->with('success', 'Materia prima eliminada correctamente.');
            }
        } catch (\Exception $e) {
            return redirect()->route('lista-item')->with('error', 'Error al eliminar materia prima.');
        }
    }
}
