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
        'priority_label',
        'project_id',
        'created_by',
        'updated_by',
        'title',
        'tag',
        'description',
        'color',
        'hidden',
        'progress',
        'date',
        'created_at',
        'updated_at',
        'list_id',
        'is_delete',
        'deleted_at'
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
        return $this->belongsTo('App\Multiple_list', 'list_id', 'id');
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
        return $this->hasMany('App\Files', 'tasks_id', 'id')->orderBY('id','desc')->with('user');
    }

    public function board()
    {
        return $this->belongsTo(self::class, 'id', 'board_parent_id');
    }

    public function task()
    {
        return $this->hasMany(self::class, 'board_parent_id', 'id')->with('Assign_tags','parents','childTask')->orderBy('board_sort_id','ASC')->orderBy('parent_id','ASC'); //comment,
    }

    public function childTask()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->with('childTask')->orderBy('sort_id');
    }

    public function linkToList()
    {
        return $this->hasMany(LinkListToColumn::class, 'task_list_id', 'id')->with('linkToListColumn');
    }

    public function child()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->with('child');
    }


    public function parents()
    {
        return $this->hasMany(self::class, 'id', 'parent_id')->with('parents');
    }

    public function column()
    {
        return $this->hasOne(self::class, 'id', 'board_parent_id')->with('MultipleBord');
    }

    public function MultipleBord()
    {
        return $this->hasOne(Multiple_board::class, 'id', 'multiple_board_id');
    }

    public function moveTo()
    {
        return $this->belongsTo(Rules::class, 'move_to', 'id');
    }

    public function moveFrom()
    {
        return $this->belongsTo(Rules::class, 'move_from', 'id');
    }

    public function moveToCol()
    {
        return $this->hasOne(Rules::class, 'move_from', 'id')->with('moveTo');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'task_id', 'id')->where('parent_id', null)->with('user','commentReply');
    }
}
