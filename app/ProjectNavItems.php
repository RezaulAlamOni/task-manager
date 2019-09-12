<?php

namespace App;

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
}
