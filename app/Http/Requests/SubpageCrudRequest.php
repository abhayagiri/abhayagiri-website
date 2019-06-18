<?php

namespace App\Http\Requests;

use Backpack\CRUD\app\Http\Requests\CrudRequest;

class SubpageCrudRequest extends CrudRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => 'required|max:255',
            'subpath' => 'required|max:255',
            'title_en' => 'required|max:255',
            'title_th' => 'nullable|max:255',
            'local_posted_at' => 'required|date',
        ];
    }
}
