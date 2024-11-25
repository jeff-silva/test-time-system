<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Support\Fluent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TodoProject extends Model
{
    /** @use HasFactory<\Database\Factories\TodoProjectFactory> */
    use HasFactory, ModelTrait;

    protected $table = 'todo_project';
    protected $fillable = ['name'];
    protected $appends = ['computed'];

    public function searchParams()
    {
        return $this->searchParamsDefaults([]);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(TodoProjectTask::class, 'project_id', 'id');
    }

    public function getComputedAttribute()
    {
        $tasks = $this->tasks()->get();
        $data = new Fluent();
        $data->tasksTotal = $tasks->count();
        $data->tasksFinished = $tasks->filter(fn($item) => $item->finished == 1)->count();
        return $data;
    }
}
