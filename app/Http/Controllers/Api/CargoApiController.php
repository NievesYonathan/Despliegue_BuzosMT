<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cargo;

class CargoApiController extends Controller
{
    public function index()
    {
        return response()->json(Cargo::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_nombre' => 'required|string|max:255',
        ]);

        $cargo = Cargo::create([
            'car_nombre' => $request->car_nombre,
        ]);

        return response()->json([
            'status' => 201,
            'cargo' => $cargo
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $cargo = Cargo::findOrFail($id);

        $cargo->update([
            'car_nombre' => $request->car_nombre,
        ]);

        return response()->json([
            'message' => 'Cargo actualizado correctamente',
            'cargo' => $cargo
        ], 200);
    }

    // Eliminar un cargo
    public function destroy($id_cargo)
    {
        $cargo = Cargo::find($id_cargo);

        if (!$cargo) {
            return response()->json(['message' => 'Cargo no encontrado'], 404); // 404 Not Found
        }

        $cargo->delete();

        return response()->json(['message' => 'Cargo eliminado correctamente'], 200); // 200 OK
    }    
}
