<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvatar extends FormRequest
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
        $size = config('validation.avatar.max_size');
        $mimes = implode(',', config('validation.avatar.mimes'));

        return [
            'avatar' => [
                'bail',
                'required',
                'image',
                "mimetypes:{$mimes}",
                "max:{$size}",
            ],
        ];
    }
}
