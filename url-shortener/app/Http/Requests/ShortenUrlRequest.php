<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShortenUrlRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust as necessary for authorization logic
    }

    public function rules()
    {
        return [
            'original_url' => 'required|url',
            'prefix' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9]+$/'
        ];
    }
}
