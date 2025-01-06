<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'order',
        'user_id',
        'is_finalized'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }
}
