<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use CrudTrait;
    use Traits\MediaPathTrait;

    protected $table = 'settings';
    protected $fillable = ['value', 'value_media_path'];

    public function getValueMediaPathAttribute()
    {
        return $this->getMediaPathFrom('value');
    }

    public function getValueMediaUrlAttribute()
    {
        return $this->getMediaUrlFrom('value');
    }

    public function setValueMediaPathAttribute($value)
    {
        $this->setMediaPathAttributeTo('value', $value);
    }

}
