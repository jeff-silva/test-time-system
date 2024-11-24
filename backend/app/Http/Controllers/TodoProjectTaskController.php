<?php

namespace App\Http\Controllers;

use App\Models\TodoProjectTask;
use Illuminate\Http\Request;
use App\Http\Requests\TodoProjectTaskRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class TodoProjectTaskController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
        ];
    }

    public function index(Request $request)
    {
        return TodoProjectTask::search($request->all(), true);
    }

    public function store(TodoProjectTaskRequest $request)
    {
        $entity = TodoProjectTask::create($request->all());
        return ['entity' => $entity];
    }

    public function show(TodoProjectTask $todoProject)
    {
        return ['entity' => $todoProject];
    }

    public function update(TodoProjectTaskRequest $request, TodoProjectTask $todoProjectTask)
    {
        $todoProjectTask->update($request->all());
        return ['entity' => $todoProjectTask];
    }

    public function destroy(TodoProjectTask $todoProjectTask)
    {
        $todoProjectTask->delete();
        return ['entity' => $todoProjectTask];
    }
}
