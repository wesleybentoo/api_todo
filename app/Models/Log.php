<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'endpoint',
        'ip_address',
        'user_agent',
        'details',
        'action_date',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}


