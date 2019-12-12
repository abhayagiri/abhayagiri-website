<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            'language_id' => 'required',
            'author_id' => 'required',
            'title' => 'required|max:255',
            'subtitle' => 'nullable|max:255',
            'alt_title_en' => 'nullable|max:255',
            'alt_title_th' => 'nullable|max:255',
            'published_on' => 'required|date',
            'local_posted_at' => 'required|date',
        ];
    }
}
