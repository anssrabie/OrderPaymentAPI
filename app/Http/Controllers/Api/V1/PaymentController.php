<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
    }

    /**
     * Get all payments.
     */
    public function index(): JsonResponse
    {
         $payments = $this->paymentService->getUserPayments(request()->get('per_page', 10));
        return $this->returnData(PaymentResource::collection($payments)->response()->getData(),'All Payments');
    }

    /**
     * Get payments by order ID.
     */
    public function getPaymentsByOrder(int $orderId): JsonResponse
    {
        $payments = $this->paymentService->getPaymentsByOrder($orderId);
        return $this->returnData(PaymentResource::collection($payments)->response()->getData(),'Order Payments');
    }

    /**
     * Process payment for an order.
     */
    public function store(PaymentRequest $request): JsonResponse
    {
        try {
            $payment = $this->paymentService->processPayment($request->input('order_id'), $request->input('method'));
            return $this->returnData(new PaymentResource($payment),'Payment processed successfully',201);
        } catch (\Exception $exception) {
            return $this->errorMessage($exception->getMessage(),$exception->getCode());
        }
    }


}
