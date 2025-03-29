<?php

namespace App\Http\Requests\Api;

use App\Enums\PaymentMethod;
use App\Http\Requests\Api\Bases\BaseFormApiRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends BaseFormApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required','exists:orders,id'],
            'method' => ['required',Rule::in(PaymentMethod::cases())],
        ];
    }
}
