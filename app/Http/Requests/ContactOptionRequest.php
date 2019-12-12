<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactOptionRequest extends FormRequest
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
            'name_en' => 'required|max:255',
            'name_th' => 'nullable|max:255',
            'body_en' => 'required',
            'body_th' => 'nullable',
            'confirmation_en' => 'required',
            'confirmation_th' => 'nullable',
            'email' => 'required|email',
            'active' => 'boolean',
            'published' => 'boolean',
            'rank' => 'required|numeric|min:0',
        ];
    }
}
