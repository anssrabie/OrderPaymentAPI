<?php

namespace App\Services\Payments\Gateways;

interface PaymentGatewayInterface
{
    public function processPayment(float $amount): array;
}
