<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task_lists';

    protected $fillable = [
        'sort_id',
        'parent_id',
        'project_id',
        'nav_id',
        'created_by', 'updated_by',
        'title',
        'tag', 'date',
        'created_at',
        'updated_at',
        'list_id'
    ];

    public function owner()
    {
        return 0;
    }

    public function project()
    {
        //
    }
    public function List()
    {
        return $this->belongsTo('App\Multiple_list','id','list_id');
    }

    public function action_log(){

        return $this->hasMany('App\ActionLog','task_id','id');
    }
    public function files(){

        return $this->hasMany('App\Files','tasks_id','id');
    }
}
