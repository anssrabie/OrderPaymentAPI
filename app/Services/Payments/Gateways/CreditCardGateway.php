<?php

namespace App\Services\Payments\Gateways;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;

class CreditCardGateway implements PaymentGatewayInterface
{
    public function processPayment(float $amount): array
    {
        return [
            'status' => PaymentStatus::Pending->value,
            'payment_id' => uniqid('cc_'),
            'message' => 'Payment processed via Credit Card',
            'method' => PaymentMethod::CreditCard->value,
        ];
    }
}
