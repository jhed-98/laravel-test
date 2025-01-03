<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_created_by_web()
    {
        Storage::fake();

        // Crear un usuario y session
        $user = User::factory()->create();
        $this->actingAs($user);  // Simula la sesión del usuario

        //Subiendo un archivo
        $image = UploadedFile::fake()->image('image.jpg');

        $response = $this->post(route('tasks.store'), [
            'name' => 'Task 1',
            'description' => 'Description of Task 1',
            'status' => 'pending',
            'image' => $image,
        ]);

        // Verificar que la imagen fue almacenada
        Storage::assertExists('tasks/' . $image->hashName());

        $response->assertRedirect(route('tasks.index'));
    }
    public function test_task_created_not_auth_by_web()
    {
        Storage::fake();

        //Subiendo un archivo
        $image = UploadedFile::fake()->image('image.jpg');

        $response = $this->post(route('tasks.store'), [
            'name' => 'Task 1',
            'description' => 'Description of Task 1',
            'status' => 'pending',
            'image' => $image,
        ]);

        // Verifica la peticion si no esta autenticado
        $response->assertRedirect(route('login'));
    }
}
