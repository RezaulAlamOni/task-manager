<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rules extends Model
{
    protected $fillable = [
        'name',
        'status',
        'project_id',
        'move_from',
        'move_to',
        'created_by',
        'assigned_users',
    ];
}
