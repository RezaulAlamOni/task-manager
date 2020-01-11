<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamUser extends Model
{
    protected $table = 'team_users';

    public function users()
    {
        return $this->hasMany(User::class, 'id','user_id')->with('notifications');
    }

    // public function notifications()
    // {
    //     return $this->hasMany( EmailAndNotification::class, 'notification_user', 'user_id', 'notification_id')->withTimestamps();
    // }

}