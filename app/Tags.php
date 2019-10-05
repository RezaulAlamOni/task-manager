<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $fillable = [
        'task_id',
        'board_id',
        'title',
        'color',
        'created_at',
        'updated_at'
    ];


    public function task()
    {
        return $this->belongsTo(Task::class,'id','task_id');
    }

    public function board()
    {
        return $this->belongsTo(TaskBoard::class,'id','board_id');
    }

}
