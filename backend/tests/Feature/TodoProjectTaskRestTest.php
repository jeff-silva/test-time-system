<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoProjectTaskRestTest extends TestCase
{
    public function test_index(): void
    {
        $response = $this->asMainUser()->get('/api/todo_project_task');
        $response->assertStatus(200);
    }

    public function test_store(): void
    {
        $todoProject = \App\Models\TodoProject::create(['name' => 'Task Test']);

        $data = [
            'name' => 'Task ' . uniqid(),
            'project_id' => $todoProject->id,
        ];

        $response = $this->asMainUser()->post('/api/todo_project_task', $data);
        $response->assertStatus(200);
    }

    public function test_show(): void
    {
        $todoProjectTask = \App\Models\TodoProjectTask::orderBy('id', 'desc')->first();
        $response = $this->asMainUser()->get("/api/todo_project_task/{$todoProjectTask->id}");
        $response->assertStatus(200);
    }

    public function test_update(): void
    {
        $todoProjectTask = \App\Models\TodoProjectTask::orderBy('id', 'desc')->first();
        $todoProjectTask->name = 'Updated ' . uniqid();
        $response = $this->asMainUser()->put("/api/todo_project_task/{$todoProjectTask->id}", $todoProjectTask->toArray());
        $response->assertStatus(200);
    }

    public function test_destroy(): void
    {
        $todoProjectTask = \App\Models\TodoProjectTask::orderBy('id', 'desc')->first();
        $response = $this->asMainUser()->delete("/api/todo_project_task/{$todoProjectTask->id}");
        $response->assertStatus(200);
    }
}
