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
            'body' => 'required',
        ];
    }

    public function sanitize()
    {
        $safeMp3 = $this->resolveMediaPath($this->input('mp3'));
        // $safeMp3 will be relative to media. We want to set it
        // relative to media/audio.
        $AudioMp3 = $this->relativeToAudio($safeMp3);
        $this->merge(['mp3' => $AudioMp3]);
    }

    protected function relativeToAudio($path)
    {
        if ($path) {
            $audioPath = Path::resolve(public_path('media/audio'));
            $fullPath = Path::resolve(public_path('media'), $path);
            return Path::relative($audioPath, $fullPath);
        } else {
            return $path;
        }
    }

}
