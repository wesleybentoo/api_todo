<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'subtask_id',
        'status_previous_id',
        'status_new_id',
        'observation',
        'user_id',
        'changed_at',
    ];

    /**
     * Relacionamento com Tarefas.
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    /**
     * Relacionamento com Subtarefas.
     */
    public function subtask()
    {
        return $this->belongsTo(SubTask::class, 'subtask_id');
    }

    /**
     * Relacionamento com Status Anterior.
     */
    public function previousStatus()
    {
        return $this->belongsTo(Status::class, 'status_previous_id')
            ->withDefault(['name' => 'N/A']); // Valor padrão para status inexistente
    }

    /**
     * Relacionamento com Novo Status.
     */
    public function newStatus()
    {
        return $this->belongsTo(Status::class, 'status_new_id')
            ->withDefault(['name' => 'N/A']); // Valor padrão para status inexistente
    }

    /**
     * Relacionamento com o Usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->withDefault(['name' => 'Usuário Desconhecido']); // Valor padrão para usuários excluídos
    }
}

