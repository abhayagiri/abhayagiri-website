<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Venturecraft\Revisionable\RevisionableTrait;

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
        'deleted_at',
    ];
}
