<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TodoProject extends Model
{
    /** @use HasFactory<\Database\Factories\TodoProjectFactory> */
    use HasFactory, ModelTrait;

    protected $table = 'todo_project';
    protected $fillable = ['name'];

    public function searchParams()
    {
        return $this->searchParamsDefaults([]);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany('todo_project_task', 'project_id', 'id');
    }
}
