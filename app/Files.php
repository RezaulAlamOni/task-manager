<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $table = 'task_files';
    protected $fillable = [
        'file_name',
        'tasks_id' ,
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
