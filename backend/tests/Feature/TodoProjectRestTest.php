<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoProjectRestTest extends TestCase
{
    public function test_index(): void
    {
        $response = $this->asMainUser()->get('/api/todo_project');
        $response->assertStatus(200);
    }

    public function test_store(): void
    {
        $data = ['name' => 'Test ' . uniqid()];
        $response = $this->asMainUser()->post('/api/todo_project', $data);
        $response->assertStatus(200);
    }

    public function test_show(): void
    {
        $todoProject = \App\Models\TodoProject::orderBy('id', 'desc')->first();
        $response = $this->asMainUser()->get("/api/todo_project/{$todoProject->id}");
        $response->assertStatus(200);
    }

    public function test_update(): void
    {
        $todoProject = \App\Models\TodoProject::orderBy('id', 'desc')->first();
        $todoProject->name = 'Updated ' . uniqid();
        $response = $this->asMainUser()->put("/api/todo_project/{$todoProject->id}", $todoProject->toArray());
        $response->assertStatus(200);
    }

    public function test_destroy(): void
    {
        $todoProject = \App\Models\TodoProject::orderBy('id', 'desc')->first();
        $response = $this->asMainUser()->delete("/api/todo_project/{$todoProject->id}");
        $response->assertStatus(200);
    }
}
