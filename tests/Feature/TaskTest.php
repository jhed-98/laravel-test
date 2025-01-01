<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    //Refrescar la BD
    use RefreshDatabase;

    public function test_task_created_success()
    {
        $response = $this->post(route('tasks.store'), [
            'name' => 'Task 1',
            'description' => 'Description of Task 1',
            'status' => 'pending',
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'name' => 'Task 1',
            'description' => 'Description of Task 1',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'Task 1',
            'description' => 'Description of Task 1',
            'status' => 'pending',
        ]);
    }

    public function tesst_no_validations_passed()
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
