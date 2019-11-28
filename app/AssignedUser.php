<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignedUser extends Model
{
    protected $table = 'task_assigned_users';

    protected $fillable = [
        'user_id',
        'task_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function taskComment()
    {
        return $this->hasMany(Comment::class,'task_id','task_id');
    }
}
