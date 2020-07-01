<?php

namespace App\Models\Setting;

use App\Models\Setting;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Parental\HasParent;

class TextSetting extends Setting
{
    use HasParent;
    use LocalizedText;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'text_en', 'text_th', 'value'];

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
            'name' => 'text_en',
            'label' => 'English',
            'type' => 'textarea',
        ]);
        $controller->crud->addField([
            'name' => 'text_th',
            'label' => 'Thai',
            'type' => 'textarea',
        ]);
    }

    /**
     * Return HTML for the value column in the admin CRUD list.
     *
     * @return string
     */
    public function getCrudListHtml(): string
    {
        return $this->limitCrudListHtml($this->text_en);
    }

    /**
     * Get the admin CRUD validation rules.
     *
     * @return array
     */
    public function getCrudRules(): array
    {
        return [
            'text_en' => 'required',
            'text_th' => 'nullable',
        ];
    }
}
