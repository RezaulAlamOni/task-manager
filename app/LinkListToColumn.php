<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkListToColumn extends Model
{
    protected $fillable = [
        'multiple_list_id',
        'task_list_id',
        'created_at',
        'updated_at'
    ];

    public function linkToList()
    {
        return $this->belongsTo(Task::class, 'task_list_id', 'id');
    }

    public function linkToListColumn()
    {
        return $this->belongsTo(Multiple_list::class, 'multiple_list_id', 'id');
    }
}
