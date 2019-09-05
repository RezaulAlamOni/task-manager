<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table ='task_lists';

    protected $fillable =[
        'sort_id','parent_id','project_id','created_by','updated_by','title','tag','date','created_at','updated_at'
    ];

    public function owner(){
        return 0;
    }
    public function project(){
        //
    }
}
