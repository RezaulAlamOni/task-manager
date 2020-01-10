<?php

namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;

class ProjectNavItems extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'type',
        'sort_id',
        'created_at',
        'updated_at'
    ];

    public function All_list(){
//        ->where('is_delete',0)
        return $this->hasMany('App\Multiple_list', 'nav_id', 'id')->with('tasks_list');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');//->where('team_id', Auth::user()->current_team_id);
    }

}
