<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Existing_tasks_in_board extends Model
{   
    protected $fillable = [
        'task_id','board_id','date'
    ];

    protected $table = 'existing_tasks_in_boards';

    public function task(){
        return $this->hasMany(Task::class, 'id', 'task_id')->with('tags');
    }
}
