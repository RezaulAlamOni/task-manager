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
       return $this->hasMany(Multiple_list::Class,'project_id','id');
    }
}
