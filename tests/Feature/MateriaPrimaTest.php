<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\MateriaPrima;
use App\Constantes\Mensajes;


class MateriaPrimaTest extends TestCase
{
    //use RefreshDatabase;

    public function test_consulta_de_toda_la_materia_prima()
    {
        MateriaPrima::factory()->count(3)->create();

        $response = $this->getJson('/api/materia-prima');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'materias_primas' => [
                    '*' => [
                        'id_materia_prima',
                        'mat_pri_nombre',
                        'mat_pri_descripcion',
                        'mat_pri_unidad_medida',
                        'mat_pri_cantidad',
                        'mat_pri_estado',
                        'fecha_compra_mp',
                        'proveedores_id_proveedores'
                    ]
                ]
            ]);
    }

    public function test_crear_materia_prima()
    {
        $payload = [
            'mat_pri_nombre' => Mensajes::TELA_NEGRA,
            'mat_pri_descripcion' => 'Tela poliéster negra',
            'mat_pri_unidad_medida' => 'metros',
            'mat_pri_cantidad' => 100,
            'mat_pri_estado' => 1,
            'fecha_compra_mp' => '2025-06-01',
            'proveedores_id_proveedores' => 4869681
        ];

        $response = $this->postJson('/api/materia-prima-agregar', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'materia_prima' => [
                         'mat_pri_nombre' => Mensajes::TELA_NEGRA
                     ]
                 ]);

        $this->assertDatabaseHas('materia_prima', [
            'mat_pri_nombre' => Mensajes::TELA_NEGRA
        ]);
    }

    public function test_mostrar_materia_prima()
    {
        $materia = MateriaPrima::factory()->create([
            'mat_pri_nombre' => 'Hilo especial'
        ]);

        $response = $this->getJson("/api/materia-prima-detalles/{$materia->id_materia_prima}");

        $response->assertStatus(200)
                 ->assertJson([
                     'materia_prima' => [
                         'id_materia_prima' => $materia->id_materia_prima,
                         'mat_pri_nombre' => 'Hilo especial'
                     ],
                     'status' => 200
                 ]);
    }

    public function test_actualizar_materia_prima()
    {
        $materia = MateriaPrima::factory()->create([
            'mat_pri_nombre' => 'Botones grandes'
        ]);

        $nuevoNombre = 'Botones pequeños';

        $response = $this->putJson("/api/materia-prima-editar/{$materia->id_materia_prima}", [
            'mat_pri_nombre' => $nuevoNombre,
            'mat_pri_descripcion' => $materia->mat_pri_descripcion,
            'mat_pri_unidad_medida' => $materia->mat_pri_unidad_medida,
            'mat_pri_cantidad' => $materia->mat_pri_cantidad,
            'mat_pri_estado' => $materia->mat_pri_estado,
            'fecha_compra_mp' => $materia->fecha_compra_mp,
            'proveedores_id_proveedores' => $materia->proveedores_id_proveedores
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'mat_pri_nombre' => $nuevoNombre
                 ]);

        $this->assertDatabaseHas('materia_prima', [
            'id_materia_prima' => $materia->id_materia_prima,
            'mat_pri_nombre' => $nuevoNombre
        ]);
    }

    public function test_eliminar_materia_prima()
    {
        $materia = MateriaPrima::factory()->create();

        $response = $this->deleteJson("/api/materia-prima/{$materia->id_materia_prima}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Materia prima eliminada correctamente'
                 ]);

        $this->assertDatabaseMissing('materia_prima', [
            'id_materia_prima' => $materia->id_materia_prima
        ]);
    }
}