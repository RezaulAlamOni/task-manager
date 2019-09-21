<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskBoard extends Model
{
    protected $fillable = [
        'parent_id','sort_id','project_id','title','nav_id','multiple_board_id','date','created_by','color','tags','hidden'
    ];
}
