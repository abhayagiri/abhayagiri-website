<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Backpack\CRUD\app\Http\Requests\CrudRequest;

class TagCrudRequest extends CrudRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title_en' => 'required|max:255',
            'rank' => 'required|numeric|min:0',
            'genre_id' => 'required',
        ];
    }

}
