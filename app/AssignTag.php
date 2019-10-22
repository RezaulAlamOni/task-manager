<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignTag extends Model
{
    protected $fillable = [
        'task_id',
        'tag_id'
    ];

    public function task()
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }

    public function tag()
    {
        return $this->hasOne(Tags::class, 'id', 'tag_id');
    }
    public function dont_forget()
    {
        return $this->hasOne(Tags::class, 'id', 'tag_id')->where('title','Dont Forget');
    }

}
