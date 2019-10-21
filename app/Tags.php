<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $fillable = [
        'team_id',
        'title',
        'color',
        'created_at',
        'updated_at'
    ];


    public function assign_to()
    {
        return $this->hasOne(AssignTag::class, 'tag_id', 'id')->with('task');
    }

}
