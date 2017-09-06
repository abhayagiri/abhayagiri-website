<?php

namespace App\Http\Requests;

use Weevers\Path\Path;

class AuthorCrudRequest extends AppCrudRequest
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
            'slug' => 'required|max:255|unique:authors,slug,' . $this->input('id'),
            'title_en' => 'required|max:255',
        ];
    }

    public function sanitize()
    {
        $safeImagePath = $this->resolveMediaPath($this->input('image_path'));
        $this->merge(['image_path' => $safeImagePath]);
    }

}
