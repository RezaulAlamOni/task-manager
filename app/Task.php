<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task_lists';

    protected $fillable = [
        'multiple_board_id',
        'sort_id',
        'board_sort_id',
        'board_flag',
        'parent_id',
        'board_parent_id',
        'project_id',
        'created_by',
        'updated_by',
        'title',
        'tag',
        'color',
        'hidden',
        'progress',
        'date',
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
        return $this->belongsTo('App\Multiple_list', 'id', 'list_id');
    }

    public function existingTaskInBoard()
    {
        return $this->belongsTo(ExistingTasksInBoard::class, 'task_id', 'id');
    }

    public function action_log()
    {
        return $this->hasMany('App\ActionLog', 'task_id', 'id');
    }

    public function Assign_tags()
    {
        return $this->hasMany(AssignTag::class, 'task_id', 'id')->with('tag');
    }
    public function Dont_Forget_tag()
    {
        return $this->hasMany(AssignTag::class, 'task_id', 'id')->with('dont_forget');
    }

    public function boardTasksTags()
    {
        return $this->hasMany(Tags::class, 'board_id', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\Files', 'tasks_id', 'id')->orderBY('id','desc');
    }

    public function board()
    {
        return $this->belongsTo(self::class, 'id', 'board_parent_id');
    }

    public function task()
    {
        return $this->hasMany(self::class, 'board_parent_id', 'id')->with('Assign_tags')->orderBy('board_sort_id','ASC');
    }
}
