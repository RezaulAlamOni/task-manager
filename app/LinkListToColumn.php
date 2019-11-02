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
}
