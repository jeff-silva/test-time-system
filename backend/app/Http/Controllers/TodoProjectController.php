<?php

namespace App\Http\Controllers;

use App\Models\TodoProject;
use Illuminate\Http\Request;
use App\Http\Requests\TodoProjectRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class TodoProjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
        ];
    }

    public function index(Request $request)
    {
        return TodoProject::search($request->all(), true);
    }

    public function store(TodoProjectRequest $request)
    {
        return [
            'entity' => TodoProject::create($request->all()),
        ];
    }

    public function show(TodoProject $todoProject)
    {
        return [
            'entity' => $todoProject,
        ];
    }

    public function update(TodoProjectRequest $request, TodoProject $todoProject)
    {
        $todoProject->update($request->all());
        return [
            'entity' => $todoProject,
        ];
    }

    public function destroy(TodoProject $todoProject)
    {
        $todoProject->delete();
        return ['entity' => $todoProject];
    }
}
