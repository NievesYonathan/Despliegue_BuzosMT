<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\TipoDoc;

class TipoDocTest extends TestCase
{
   // use RefreshDatabase;

    public function test_mostrar_documentos()
    {
        TipoDoc::factory()->create(['tip_doc_descripcion' => 'Cédula']);
        TipoDoc::factory()->create(['tip_doc_descripcion' => 'Pasaporte']);

        $response = $this->getJson('/api/tipos-documentos');

        $response->assertStatus(200)
                 ->assertJsonFragment(['tip_doc_descripcion' => 'Cédula'])
                 ->assertJsonFragment(['tip_doc_descripcion' => 'Pasaporte']);
    }

    public function test_crear_documentos()
    {
        $data = ['tip_doc_descripcion' => 'PPT'];

        $response = $this->postJson('/api/tipos-documentos', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment($data);

        $this->assertDatabaseHas('tipo_doc', $data);
    }

    public function test_actualizar_documentos()
    {
        $tipoDoc = TipoDoc::factory()->create(['tip_doc_descripcion' => 'Tarjeta de Identidad']);

        $data = ['tip_doc_descripcion' => 'TI Actualizada'];

        $response = $this->putJson("/api/tipos-documentos/{$tipoDoc->id_tipo_documento}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment($data);

        $this->assertDatabaseHas('tipo_doc', $data);
    }

    public function test_eliminar_documentos()
    {
        $tipoDoc = TipoDoc::factory()->create(['tip_doc_descripcion' => 'Documento Temporal']);

        $response = $this->deleteJson("/api/tipos-documentos/{$tipoDoc->id_tipo_documento}");

        $response->assertStatus(200)
                ->assertJson(['message' => 'tipoDoc eliminado correctamente']);

        $this->assertDatabaseMissing('tipo_doc', ['id_tipo_documento' => $tipoDoc->id_tipo_documento]);
    }
}
