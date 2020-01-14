<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Spark\CanJoinTeams;
use Laravel\Spark\User as SparkUser;

class User extends SparkUser implements MustVerifyEmail
{
    use Notifiable;
    use CanJoinTeams;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'card_brand',
        'card_last_four',
        'card_country',
        'billing_address',
        'billing_address_line_2',
        'billing_state',
        'billing_city',
        'billing_zip',
        'billing_country',
        'vat_id',
        'extra_billing_information',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'authy_id',
        'country_code',
        'phone',
        'card_brand',
        'card_last_four',
        'card_country',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_zip',
        'billing_country',
        'extra_billing_information',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'uses_two_factor_auth' => 'boolean',
    ];
    // public function teams()
    // {
    //     return $this->belongsToMany(Team::class, 'user_id','id');
    // }
    public function notifications()
    {
        return $this->belongsToMany(EmailAndNotification::class, 'notification_user', 'user_id', 'notification_id')->withTimestamps();
    }

    public function user_teams()
    {
        return $this->belongsToMany(Team::class, 'team_users', 'user_id', 'team_id');
    }

    /**
     * Get Tasks Assigned to user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_assigned_users', 'user_id', 'task_id')->withTimestamps();
    }
}
