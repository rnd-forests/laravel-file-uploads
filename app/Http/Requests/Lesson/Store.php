<?php

namespace App\Http\Requests\Lesson;

use App\Rules\Version;
use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $size = config('validation.audio.max_size');
        $mimes = implode(',', config('validation.audio.mimes'));

        return [
            'title' => 'required|string|max:255|unique:lessons',
            'description' => 'required|string',
            'version' => [
                'required',
                'string',
                'max:15',
                resolve(Version::class),
            ],
            'audio' => [
                'bail',
                'required',
                'file',
                "mimetypes:{$mimes}",
                "max:{$size}",
            ],
        ];
    }
}
