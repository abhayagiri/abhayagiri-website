<?php

namespace App\Http\Requests;

class GenreCrudRequest extends AppCrudRequest
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
            'slug' => 'required|max:255|unique:genres',
            'title_en' => 'required|max:255',
            'rank' => 'required|numeric|min:0'
        ];
    }

    public function sanitize()
    {
        if ($this->input('slug')) {
            $this->merge(['slug'] => str_slug($this->input('slug')));
        }
        $safeImagePath = $this->resolveMediaPath($this->input('image_path'));
        $this->merge(['image_path' => $safeImagePath]);
    }

}
