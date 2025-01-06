<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status_id',
        'task_id'
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'subtask_id');
    }
}

