<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    use SoftDeletes;
    protected $dates = ['deleted_at'];

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
