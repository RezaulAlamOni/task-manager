<?php

namespace App;

use Laravel\Spark\Team as SparkTeam;

class Team extends SparkTeam
{
    protected $table = 'teams';

    public function team_users()
    {
        return $this->belongsToMany(User::class, 'team_users','team_id','user_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'team_id', 'id');
    }

}
