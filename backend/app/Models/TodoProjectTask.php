<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TodoProjectTask extends Model
{
    /** @use HasFactory<\Database\Factories\TodoProjectTaskFactory> */
    use HasFactory, ModelTrait;

    protected $table = 'todo_project_task';
    protected $fillable = ['name', 'description', 'finished', 'project_id'];

    public function searchQuery($query, $params)
    {
        if ($params->project_id) {
            $query->where('project_id', $params->project_id);
        }

        return $query;
    }

    public function project(): HasOne
    {
        return $this->hasOne('todo_project', 'id', 'project_id');
    }
}
