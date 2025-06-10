<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cargo;

class CargoTest extends TestCase
{
    use RefreshDatabase;

    public function test_listar_todos_los_cargos()
    {
        Cargo::factory()->count(3)->create();

        $response = $this->getJson('/api/cargos');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'id_cargos',
                         'car_nombre',
                     ]
                 ]);
    }

    public function test_crear_un_cargo()
    {
        $payload = [
            'car_nombre' => 'Supervisor de producción',
        ];

        $response = $this->postJson('/api/cargos', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'cargo' => [
                         'car_nombre' => 'Supervisor de producción'
                     ]
                 ]);

        $this->assertDatabaseHas('cargos', $payload);
    }

    public function test_editar_un_cargo()
    {
        // Crear un cargo inicial
        $cargo = Cargo::factory()->create([
            'car_nombre' => 'Auxiliar'
        ]);

        // Datos actualizados
        $datosActualizados = [
            'car_nombre' => 'Supervisor'
        ];

        // Petición PUT para actualizar el cargo
        $response = $this->putJson("/api/cargos/{$cargo->id_cargos}", $datosActualizados);

        // Verifica respuesta exitosa
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Cargo actualizado correctamente',
                     'cargo' => [
                         'id_cargos' => $cargo->id_cargos,
                         'car_nombre' => 'Supervisor'
                     ]
                 ]);

        // Verifica que en la base de datos se actualizó
        $this->assertDatabaseHas('cargos', [
            'id_cargos' => $cargo->id_cargos,
            'car_nombre' => 'Supervisor'
        ]);
    }    

    public function test_eliminar_un_cargo()
    {
        $cargo = Cargo::factory()->create();

        $response = $this->deleteJson("/api/cargos-eliminar/{$cargo->id_cargos}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Cargo eliminado correctamente'
                 ]);

        $this->assertDatabaseMissing('cargos', [
            'id_cargos' => $cargo->id_cargos
        ]);
    }
}