<?php

namespace App\Http\Requests\Api\Bases;

use App\Traits\ApiTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseFormApiRequest extends FormRequest
{
    use ApiTrait;

    protected function failedValidation(Validator $validator)
    {
        $firstError = $validator->errors()->first();
        throw new HttpResponseException(
            $this->errorMessage($firstError)
        );
    }
}
