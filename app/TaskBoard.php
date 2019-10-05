<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskBoard extends Model
{
    protected $fillable = [
        'parent_id','sort_id','project_id','nav_id','progress','title','hidden','multiple_board_id','date','created_by','updated_by','color','tags'
    ];

    public function tags(){
        return $this->hasMany(Tags::class,'board_id','id');
    }
}
