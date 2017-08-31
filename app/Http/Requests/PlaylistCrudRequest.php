<?php

namespace App\Http\Requests;

class PlaylistCrudRequest extends AppCrudRequest
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
            'slug' => 'required|max:255|unique:playlists,slug,' . $this->input('id'),
            'title_en' => 'required|max:255',
            'rank' => 'required|numeric|min:0'
        ];
    }

    public function sanitize()
    {
        $safeImagePath = $this->resolveMediaPath($this->input('image_path'));
        $this->merge(['image_path' => $safeImagePath]);
    }

}
