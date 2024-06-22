<?php
namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UrlValidationService
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'original_url' => 'required|url',
            'prefix' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9]+$/'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
