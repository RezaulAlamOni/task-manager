<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'name', 'description' , 'created_by', 'updated_by'
    ];

    public function multiple_list(){
        $this->hasMany('App\Multiple_list','project_id','id');
    }
}
