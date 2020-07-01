<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SettingRequest extends FormRequest
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
        if (!$this->input('id')) {
            return [];
        }
        $this->getInputSource()->remove('type');
        $this->getInputSource()->remove('key');
        $setting = Setting::find($this->input('id'));
        if (!$setting) {
            $this->merge(['setting' => null]);
            return [
                'setting' => 'required',
            ];
        }
        return $setting->getCrudRules();
    }
}
