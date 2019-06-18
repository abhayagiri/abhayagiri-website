<?php

namespace App\Http\Requests;

use Backpack\CRUD\app\Http\Requests\CrudRequest;

class SubjectCrudRequest extends CrudRequest
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
            'group_id' => 'required',
            'title_en' => 'required|max:255|unique:subjects,title_en,' . $this->input('id'),
            'title_th' => 'nullable|max:255|unique:subjects,title_th,' . $this->input('id'),
            'rank' => 'required|numeric|min:0',
        ];
    }
}
