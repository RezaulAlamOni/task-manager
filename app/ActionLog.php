<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'multiple_list_id',
        'task_id',
        'multiple_board_id',
        'board_id',
        'title',
        'log_type',
        'action_type',
        'action_by',
        'action_at'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'action_by');
    }
}
