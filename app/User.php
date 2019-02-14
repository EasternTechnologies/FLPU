<?php

namespace App;

use App\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name',
      'email',
      'surname',
      'organization',
      'tel1',
      'tel2',
      'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'password',
      'remember_token',
    ];

    public function roles () {
        return $this->belongsToMany('App\Role');
    }

    public function isadmin () {

        foreach ( $this->roles as $user_role ) {
            if ( $user_role->title == 'admin' ) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }

    }

    public function isuser () {

        foreach ( $this->roles as $user_role ) {
            if ( $user_role->title == 'user' ) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }

    }
    
    public function isemployee () {

        foreach ( $this->roles as $user_role ) {
            if ( $user_role->title != 'employee' ) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }

    }


    public function isanalyst () {

        foreach ( $this->roles as $user_role ) {
            if ( $user_role->title == 'analyst' or $user_role->title == 'admin' ) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }

    }

    public function ismanager () {

        foreach ( $this->roles as $user_role ) {
            if ( $user_role->title == 'manager' or $user_role->title == 'admin' ) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }

    }
    public function manager () {

        foreach ( $this->roles as $user_role ) {
            if ( $user_role->title == 'manager' ) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }

    }

    public function getIsAdminAttribute()
    {
        foreach ( $this->roles as $user_role ) {
            if ( $user_role->title == 'admin' ) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
