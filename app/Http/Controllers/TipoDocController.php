<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\TipoDoc;
use App\Constantes\Mensajes;

class TipoDocController extends Controller
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
            // $response = Http::get("{$this->apiBase}/tipos-documentos");

            // if (!$response->successful()) {
            //     throw new \Exception('Error al obtener tipos de documentos');
            // }

            // return view('Perfil-Admin-Usuarios.tipoDocumentos', [
            //     'tipoDocumentos' => $response->json()
            // ]);

            $tipoDocumentos = TipoDoc::all(); // Reemplaza con tu lógica
            return view('Perfil-Admin-Usuarios.tipoDocumentos', compact('tipoDocumentos'));
        } catch (\Exception $e) {
            return back()->with('error', Mensajes::ERROR_SERVER);
        }
    }

    public function create()
    {
        return view('Perfil-Admin-Usuarios.tipoDocumentos-new');
    }

    public function store(Request $request)
    {
        try {
            // $response = Http::post("{$this->apiBase}/tipos-documentos", $request->all());

            // if ($response->successful()) {
            //     return redirect()->route('tipoDocumentos')->with('success', 'Tipo de documento creado correctamente');
            // }

            // $errorMessage = $response->json()['message'] ?? 'Error al crear tipo de documento';
            // return back()->withErrors(['error' => $errorMessage])->withInput();

            // Validar los datos antes de almacenarlos
            $validated = $request->validate([
                'tip_doc_descripcion' => 'required|string|max:255',  // Validación del campo nombre

            ]);

            // Crear un nuevo tipo de documento
            $tipoDocumentos = new TipoDoc();
            $tipoDocumentos->tip_doc_descripcion = $validated['tip_doc_descripcion'];


            // Guardar en la base de datos
            $tipoDocumentos->save();

            // Redirigir o devolver una respuesta (puede ser JSON, o redirigir a la lista)
            return redirect()->route('tipoDocumentos')->with('success', 'Tipo de documento creado correctamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => Mensajes::ERROR_SERVER])->withInput();
        }
    }

    public function update(Request $request, $id_tipo_documento)
    {
        try {
            // $response = Http::put("{$this->apiBase}/tipos-documentos/{$id_tipo_documento}", $request->all());

            // if ($response->successful()) {
            //     return redirect()->route('tipoDocumentos')->with('success', 'Descripción actualizada correctamente');
            // }

            // return back()->withErrors(['error' => $response->json()['message'] ?? 'Error al actualizar']);

            $tipoDocumentos = TipoDoc::where('id_tipo_documento', $id_tipo_documento)->first();


            // Actualiza solo los campos que están presentes en el request
            $tipoDocumentos->update([
                'tip_doc_descripcion' => $request->tip_doc_descripcion,

            ]);

            return redirect()->route('tipo-documentos')->with('success', 'descripcion actualizada correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => Mensajes::ERROR_SERVER]);
        }
    }

    public function destroy($id)
    {
        try {
            $tipoDocumento = TipoDoc::findOrFail($id);
                
            // Verificar si el cargo está asignado a usuarios
            if ($tipoDocumento->usuarios()->exists()) {
                return back()->withErrors(['error' => 'No se puede eliminar el Documento porque está asignado a usuarios.']);
            }
            
            $tipoDocumento->delete();

            return redirect()->route('tipo-documentos')->with('success', 'Documento eliminado correctamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'No se pudo eliminar el Documento.']);
        }
    }
}
