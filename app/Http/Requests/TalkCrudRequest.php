<?php

namespace App\Http\Requests;

use Weevers\Path\Path;

class TalkCrudRequest extends AppCrudRequest
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
            'title' => 'required|max:255',
            'title_th' => 'max:255',
            'author_id' => 'required',
            'type_id' => 'required',
        ];
    }

    public function sanitize()
    {
        $safeMp3 = $this->resolveMediaPath($this->input('mp3'), 'audio');
        $this->merge(['mp3' => $safeMp3]);
    }

}
