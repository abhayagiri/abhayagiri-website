<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Backpack\CRUD\app\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use CrudTrait;
    use Notifiable;
    use RevisionableTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_super_admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'remember_token', 'deleted_at',
    ];

    /**
     * Override to store the creation as a revision
     *
     * @var bool
     */
    protected $revisionCreationsEnabled = true;

    /**
     * Alias for is_super_admin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->is_super_admin;
    }

      /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }
}
