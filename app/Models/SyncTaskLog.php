<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncTaskLog extends Model
{
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'log' => '',
    ];

    /**
     * The storage format of the model's date columns.
     *
     * This supports microsecond timestamps for created_at / updated_at.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*****************
     * Relationships *
     *****************/

    public function logs() : BelongsTo
    {
        return $this->belongsTo('App\Models\SyncTask');
    }
}
