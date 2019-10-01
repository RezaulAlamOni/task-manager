<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $fillable = [
        'task_id',
        'title',
        'color',
        'created_at',
        'updated_at'
    ];


    public function task()
    {
        return $this->belongsTo(Task::class,'id','task_id');
    }

}
