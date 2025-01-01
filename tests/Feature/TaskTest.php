<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskTest extends TestCase
{
    //Refrescar la BD
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_task_created_success(): void
    {
        Storage::fake();

        $image = UploadedFile::fake()->image('image.jpg');

        $response = $this->post(route('tasks.store'), [
            'name' => 'Task 1',
            'description' => 'Description of Task 1',
            'status' => 'pending',
            'image' => $image,
        ]);

        $response->assertStatus(200);

        Storage::assertExists('tasks/' . $image->hashName());

        $response->assertJson([
            'name' => 'Task 1',
            'description' => 'Description of Task 1',
            'status' => 'pending',
            'image' => 'tasks/' . $image->hashName(),
        ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'Task 1',
            'description' => 'Description of Task 1',
            'status' => 'pending',
            'image' => 'tasks/' . $image->hashName(),
        ]);
    }

    public function test_no_validations_passed()
    {
        $response = $this->post(route('tasks.store'), [
            'name' => '',
            'description' => '',
            'status' => '',
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
    }
}
