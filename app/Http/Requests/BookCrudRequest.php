<?php

namespace App\Http\Requests;

use Weevers\Path\Path;

class BookCrudRequest extends AppCrudRequest
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
            'language_id' => 'required',
            'author_id' => 'required',
            'title' => 'required|max:255',
            'subtitle' => 'max:255',
            'alt_title_en' => 'max:255',
            'alt_title_th' => 'max:255',
            'published_on' => 'required',
            'local_posted_at' => 'required',
        ];
    }

    public function sanitize()
    {
        $this->merge([
            'image_path' => $this->resolveMediaPath($this->input('image_path')),
            'pdf_path' => $this->resolveMediaPath($this->input('pdf_path')),
            'epub_path' => $this->resolveMediaPath($this->input('epub_path')),
            'mobi_path' => $this->resolveMediaPath($this->input('mobi_path')),
        ]);
    }

}
