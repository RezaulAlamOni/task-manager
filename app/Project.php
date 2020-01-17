<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'team_id',
        'name',
        'description',
        'created_by',
        'updated_by'
    ];

    public function multiple_list()
    {
        return $this->hasMany(Multiple_list::Class, 'project_id', 'id');
    }

    public function multiple_board()
    {
        return $this->hasMany(Multiple_board::Class, 'project_id', 'id');
    }

    public function action_log()
    {
        return $this->hasMany('App\ActionLog', 'project_id', 'id');
    }

    public function project_nav()
    {
        return $this->hasMany(ProjectNavItems::class, 'project_id', 'id');
    }

    /**
     * Get the team
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id')->with('team_users');
    }
}
