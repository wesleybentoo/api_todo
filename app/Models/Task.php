<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'status_id',
        'category_id',
        'due_date',
    ];

    /**
     * Relacionamento: Uma tarefa pertence a um usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relacionamento: Uma tarefa possui um status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    /**
     * Relacionamento: Uma tarefa pertence a uma categoria.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relacionamento: Uma tarefa pode ter várias subtarefas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subtasks()
    {
        return $this->hasMany(SubTask::class, 'task_id');
    }

    /**
     * Relacionamento com o histórico de atividades.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'task_id');
    }
}
