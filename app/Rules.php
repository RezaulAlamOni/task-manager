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

    public function move_from()
    {
        return $this->hasOne(Task::class, 'id', 'move_from');
    }
    public function move_to()
    {
        return $this->hasOne(Task::class, 'id', 'move_to');
    }


}
