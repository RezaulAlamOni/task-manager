<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'parent_id',
        'comment',
        'attatchment',
        'created_at',
        'updated_at'
    ];

    public function user ()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function task()
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }

    public function commentReply ()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->with('user', 'commentReply');
    }

}
