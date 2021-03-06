<?php

namespace App\Models\Setting;

use App\Models\Setting;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Parental\HasParent;

class NumberSetting extends Setting
{
    use HasParent;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'value'];

    /**
     * Callback for adding admin CRUD fields.
     *
     * @param  \Backpack\CRUD\app\Http\Controllers\CrudController  $controller
     *
     * @return void
     */
    public function addCrudFields(CrudController $controller): void
    {
        $controller->crud->addField([
            'name' => 'value',
            'label' => 'Value',
            'type' => 'number',
        ]);
    }
}
