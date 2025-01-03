<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskApiTest extends TestCase
{
    //Refrescar la BD
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    // public function test_task_created_success(): void
    // {
    //     Storage::fake();

    //     //Subiendo un archivo

    //     $image = UploadedFile::fake()->image('image.jpg');

    //     $response = $this->postJson(route('tasks.store'), [
    //         'name' => 'Task 1',
    //         'description' => 'Description of Task 1',
    //         'status' => 'pending',
    //         'image' => $image,
    //     ]);

    //     $response->assertStatus(200);

    //     // Verificar que la imagen fue almacenada
    //     Storage::assertExists('tasks/' . $image->hashName());

    //     $this->assertDatabaseHas('tasks', [
    //         'name' => 'Task 1',
    //         'description' => 'Description of Task 1',
    //         'status' => 'pending',
    //         'image' => 'tasks/' . $image->hashName(),
    //     ]);
    // }

    /** API **/
    public function test_task_created_by_api_success(): void
    {
        Storage::fake();

        // Crear un usuario y token
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        //Subiendo un archivo
        $image = UploadedFile::fake()->image('image.jpg');

        $response = $this->postJson(route('api.tasks.store'), [
            'name' => 'Task 1',
            'description' => 'Description of Task 1',
            'status' => 'pending',
            'image' => $image,
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);

        // Verificar que la imagen fue almacenada
        Storage::assertExists('tasks/' . $image->hashName());

        $response->assertJson([
            'name' => 'Task 1',
            'description' => 'Description of Task 1',
            'status' => 'pending',
            'image' => 'tasks/' . $image->hashName(),
            'user_id' => $user->id
        ]);
    }
    public function test_task_not_created_auth_by_api()
    {
        Storage::fake();

        //Subiendo un archivo
        $image = UploadedFile::fake()->image('image.jpg');

        $response = $this->postJson(route('api.tasks.store'), [
            'name' => 'Task 1',
            'description' => 'Description of Task 1',
            'status' => 'pending',
            'image' => $image,
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    // public function test_no_validations_by_api_passed()
    // {
    //     $response = $this->post(route('api.tasks.store'), [
    //         'name' => '',
    //         'description' => '',
    //         'status' => '',
    //     ], [
    //         'Accept' => 'application/json'
    //     ]);

    //     $response->assertStatus(422);

    //     $response->assertJsonStructure([
    //         'message',
    //         'errors'
    //     ]);
    // }
}
