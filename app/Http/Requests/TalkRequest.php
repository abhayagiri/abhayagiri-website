<?php

namespace App\Http\Requests;

use App\Utilities\ValidateUrlForEmbed;
use Illuminate\Foundation\Http\FormRequest;

class TalkRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->input('youtube_video_id')) {
            $videoId = ValidateUrlForEmbed::forYouTube($this->input('youtube_video_id'));

            if (! $videoId) {
                return;
            }

            $this->merge([
                'youtube_video_id' => $videoId,
            ]);
        }
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
            'title_th' => 'nullable|max:255',
            'author_id' => 'required',
            'language_id' => 'required',
            'playlists' => 'required',
            'youtube_video_id' => 'nullable|max:255|unique:talks,youtube_video_id,' . $this->input('id'),
            'recorded_on' => 'required|date',
            'posted_at' => 'required|date',
        ];
    }
}
