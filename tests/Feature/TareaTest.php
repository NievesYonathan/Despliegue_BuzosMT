<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tarea;

class TareaTest extends TestCase
{
    //use RefreshDatabase;

    public function test_mostrar_tareas()
    {
        Tarea::factory()->count(3)->create();

        $response = $this->getJson('/api/tareas');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id_tarea', 'tar_nombre', 'tar_descripcion', 'tar_estado']
                 ]);
    }

    public function test_mostrar_una_tarea()
    {
        $tarea = Tarea::factory()->create();

        $response = $this->getJson("/api/tareas/{$tarea->id_tarea}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id_tarea' => $tarea->id_tarea,
                     'tar_nombre' => $tarea->tar_nombre,
                     'tar_descripcion' => $tarea->tar_descripcion,
                     'tar_estado' => $tarea->tar_estado,
                 ]);
    }

    public function test_actualizar_una_tarea()
    {
        $tarea = Tarea::factory()->create();

        $nuevosDatos = [
            'tar_nombre' => 'Tarea actualizada',
            'tar_descripcion' => 'Descripción actualizada',
            'tar_estado' => 2,
        ];

        $response = $this->putJson("/api/tareas/{$tarea->id_tarea}", $nuevosDatos);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Tarea actualizada correctamente',
                     'tarea' => [
                         'id_tarea' => $tarea->id_tarea,
                         'tar_nombre' => 'Tarea actualizada',
                         'tar_descripcion' => 'Descripción actualizada',
                         'tar_estado' => 2,
                     ]
                 ]);

        $this->assertDatabaseHas('tarea', $nuevosDatos);
    }

    public function test_eliminar_una_tarea()
    {
        $tarea = Tarea::factory()->create();

        $response = $this->deleteJson("/api/tareas/{$tarea->id_tarea}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Tarea eliminada correctamente'
                 ]);

        $this->assertDatabaseMissing('tarea', [
            'id_tarea' => $tarea->id_tarea
        ]);
    }
}
