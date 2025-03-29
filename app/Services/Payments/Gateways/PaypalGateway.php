<?php

namespace App\Services\Payments\Gateways;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;

class PaypalGateway implements PaymentGatewayInterface
{
    public function processPayment(float $amount): array
    {
        return [
            'status' => PaymentStatus::Pending->value,
            'transaction_id' => uniqid('pp_'),
            'message' => 'Payment processed via PayPal successfully.',
            'method' => PaymentMethod::PayPal->value,
        ];
    }
}
