<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Multiple_board extends Model
{
    protected $fillable = [
        'project_id','board_title','nav_id','description','created_at','updated_at'
    ];
    public function project()
    {
        return $this->belongsTo('App\Project','project_id','id');
    }
}
