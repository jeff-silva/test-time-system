<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoProjectRestTest extends TestCase
{
    public function asMainUser()
    {
        $token = auth()->tokenById(1);
        return $this->withHeaders(['Authorization' => "Bearer {$token}"]);
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    /**
     * A basic feature test example.
     */
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
        $model = \App\Models\TodoProject::orderBy('id', 'desc')->first();
        $response = $this->asMainUser()->get("/api/todo_project/{$model->id}");
        $response->assertStatus(200);
    }

    public function test_update(): void
    {
        $model = \App\Models\TodoProject::orderBy('id', 'desc')->first();
        $model->name = 'Updated ' . uniqid();
        $response = $this->asMainUser()->put("/api/todo_project/{$model->id}", $model->toArray());
        $response->assertStatus(200);
    }

    public function test_destroy(): void
    {
        $model = \App\Models\TodoProject::orderBy('id', 'desc')->first();
        $response = $this->asMainUser()->delete("/api/todo_project/{$model->id}");
        $response->assertStatus(200);
    }
}
