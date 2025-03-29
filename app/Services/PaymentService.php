<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Repositories\PaymentRepository;
use App\Services\Payments\Gateways\CreditCardGateway;
use App\Services\Payments\Gateways\PaypalGateway;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaymentService extends BaseService
{
    public function __construct(protected PaymentRepository $paymentRepository,protected OrderService $orderService)
    {
        parent::__construct($paymentRepository);
    }

    /**
     * Get paginated payments for the authenticated user.
     */
    public function getUserPayments(int $perPage = 10): LengthAwarePaginator
    {
        return $this->paymentRepository->getPaymentsForUser(auth()->id(), $perPage);
    }

    /**
     * Get all payments for a specific order.
     */
    public function getPaymentsByOrder(int $orderId): Collection
    {
        return $this->paymentRepository->getPaymentsByOrder($orderId);
    }

    /**
     * Process a payment for an order.
     */
    public function processPayment(int $orderId, string $method)
    {
        // Retrieve the order and ensure it exists
        $order = $this->orderService->showResource($orderId,['payments']);

        // Ensure that payments can only be processed for confirmed orders
        if ($order->status->value !== OrderStatus::Confirmed->value) {
            throw new Exception("Payments can only be processed for confirmed orders.", 400);
        }

        // Check if there's already a successful payment for this order
        if ($order->payments()->where('status', PaymentStatus::Successful->value)->exists()) {
            throw new Exception("This order has already been paid successfully.", 400);
        }

        // Select the appropriate payment gateway
        $gateway = match ($method) {
            'credit_card' => new CreditCardGateway(),
            'paypal' => new PayPalGateway(),
           // default => throw new Exception("Invalid payment method: $method", 400), // Handling unknown methods
        };

        $paymentResult = $gateway->processPayment($order->total_price);

        return DB::transaction(function () use ($order, $method, $paymentResult) {
            return $order->payments()->create([
                'method' => $method,
                'payment_id' => $paymentResult['payment_id'],
                'status' => $paymentResult['status'],
            ]);
        });
    }
}
