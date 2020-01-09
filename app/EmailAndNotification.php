<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailAndNotification extends Model
{
    public $timestamps = true;
    protected $hidden = ['pivot'];
    protected $guarded = ['id'];

    /**
     * Get the EmailAndNotifications associated with the parent's `id`
     */
    public function children()
    {
        return $this->hasMany('App\EmailAndNotification', 'parent_id', 'id');
    }

    /**
     * Get the parent associated with the EmailAndNotification's `parent_id`
     */
    public function parent()
    {
        return $this->belongsTo('App\EmailAndNotification');
    }

    /**
     * Get the users associated with the Email & Notifications
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id')->withTimestamps();
    }
}
