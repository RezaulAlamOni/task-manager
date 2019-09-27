<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskBoard extends Model
{
    protected $fillable = [
        'parent_id','sort_id','project_id','title','nav_id','hidden','multiple_board_id','date','created_by','updated_by','color','tags'
    ];
}
