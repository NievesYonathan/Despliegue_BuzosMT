<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UsuarioTest extends TestCase
{
    //use RefreshDatabase;

    public function test_mostrar_usuarios()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/usuarios');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['num_doc', 't_doc', 'usu_nombres', 'usu_apellidos', 'email', 'usu_estado']
                 ]);
    }

    public function test_crear_un_usuario()
    {
        $usuario = [
            'num_doc' => '123456789',
            't_doc' => 1,
            'usu_nombres' => 'Juan',
            'usu_apellidos' => 'Pérez',
            'usu_fecha_nacimiento' => '2000-01-01',
            'usu_sexo' => 'M',
            'usu_direccion' => 'Calle Falsa 123',
            'usu_telefono' => '3001234567',
            'email' => 'juan@example.com',
            'usu_fecha_contratacion' => '2024-06-01',
            'usu_estado' => 1,
            'imag_perfil' => null,
            'external_id' => null,
            'external_auth' => null,
            'password' => 'Password123!',
        ];

        $response = $this->postJson('/api/usuarios', $usuario);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Usuario creado correctamente',
                     'usuario' => [
                         'num_doc' => '123456789',
                         'usu_nombres' => 'Juan',
                         'email' => 'juan@example.com'
                     ]
                 ]);

        $this->assertDatabaseHas('usuarios', [
            'num_doc' => '123456789',
            'email' => 'juan@example.com'
        ]);
    }

    public function test_modificar_un_usuario()
    {
        $user = User::factory()->create([
            'num_doc' => '123456789',
            'usu_nombres' => 'Pedro',
            'email' => 'pedro@example.com'
        ]);

        $datosActualizados = [
            'usu_nombres' => 'Pedro Actualizado',
            'usu_apellidos' => 'García',
            'email' => 'pedro.actualizado@example.com'
        ];

        $response = $this->putJson("/api/usuarios/{$user->num_doc}", $datosActualizados);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Usuario actualizado correctamente',
                     'usuario' => [
                         'num_doc' => '123456789',
                         'usu_nombres' => 'Pedro Actualizado',
                         'email' => 'pedro.actualizado@example.com'
                     ]
                 ]);

        $this->assertDatabaseHas('usuarios', [
            'num_doc' => '123456789',
            'usu_nombres' => 'Pedro Actualizado',
            'email' => 'pedro.actualizado@example.com'
        ]);
    }
}
