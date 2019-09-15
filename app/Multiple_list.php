<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Multiple_list extends Model
{
    protected $fillable = [
        'project_id','list_title','nav_id','description','created_at','updated_at'
    ];

    public function listItem(){
        return $this->hasMany('App\Task','list_id','id');
    }
    public function project()
    {
        return $this->belongsTo('App\Project','project_id','id');
    }
    public function action_log(){

        return $this->hasMany('App\ActionLog','multiple_list_id','id');
    }
}
