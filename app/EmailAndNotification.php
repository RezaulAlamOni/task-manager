<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailAndNotification extends Model
{
    public $timestamps = false;

    /**
     * Get the Categories associated with the parent's `id`
     */
    public function children()
    {
        return $this->hasMany('App\EmailAndNotification', 'parent_id', 'id');
    }

    /**
     * Get the parent associated with the EmailAndNotification`s parent_id`
     */
    public function parent()
    {
        return $this->belongsTo('App\EmailAndNotification');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
