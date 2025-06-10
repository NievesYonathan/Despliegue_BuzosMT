<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Etapas;

class EtapasTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_una_etapa()
    {
        $payload = [
            'eta_nombre' => 'Corte',
            'eta_descripcion' => 'Etapa de corte de tela'
        ];

        $response = $this->postJson('/api/etapas', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Etapa creada correctamente',
                     'etapa' => [
                         'eta_nombre' => 'Corte',
                         'eta_descripcion' => 'Etapa de corte de tela'
                     ]
                 ]);

        $this->assertDatabaseHas('etapas', $payload);
    }

    public function test_editar_una_etapa()
    {
        $etapa = Etapas::factory()->create();

        $nuevosDatos = [
            'eta_nombre' => 'Costura',
            'eta_descripcion' => 'Etapa de costura'
        ];

        $response = $this->putJson("/api/etapas/{$etapa->id_etapas}", $nuevosDatos);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Etapa actualizada correctamente',
                     'etapa' => [
                         'id_etapas' => $etapa->id_etapas,
                         'eta_nombre' => 'Costura',
                         'eta_descripcion' => 'Etapa de costura'
                     ]
                 ]);

        $this->assertDatabaseHas('etapas', $nuevosDatos);
    }

    public function test_eliminar_una_etapa()
    {
        $etapa = Etapas::factory()->create();

        $response = $this->deleteJson("/api/etapas/{$etapa->id_etapas}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Etapa eliminada correctamente'
                 ]);

        $this->assertDatabaseMissing('etapas', [
            'id_etapas' => $etapa->id_etapas
        ]);
    }

    public function test_listar_etapas()
    {
        Etapas::factory()->count(3)->create();

        $response = $this->getJson('/api/etapas');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id_etapas', 'eta_nombre', 'eta_descripcion']
                 ]);
    }
}
