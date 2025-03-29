<?php

namespace App\Http\Requests\Api;

use App\Enums\OrderStatus;
use App\Http\Requests\Api\Bases\BaseFormApiRequest;
use Illuminate\Validation\Rule;

class OrderStatusRequest extends BaseFormApiRequest
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
            'status' => ['required',Rule::in(OrderStatus::Confirmed->value,OrderStatus::Cancelled->value)]
        ];
    }
}
