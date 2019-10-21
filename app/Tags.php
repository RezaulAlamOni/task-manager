<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $fillable = [
        'task_id',
        'status',
        'title',
        'color',
        'created_at',
        'updated_at'
    ];


    public function assign_to()
    {
        return $this->hasOne(AssignTag::class, 'tag_id', 'id')->with('task');
    }

}
