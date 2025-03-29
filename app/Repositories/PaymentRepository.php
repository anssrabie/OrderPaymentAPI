<?php

namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentRepository extends BaseRepository
{
    public function __construct(protected Payment $payment)
    {
        parent::__construct($payment);
    }

    /**
     * Get paginated payments for a specific user.
     */
    public function getPaymentsForUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->query()
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get payments by order ID
     */
    public function getPaymentsByOrder(int $orderId): Collection
    {
        return $this->query()->where('order_id', $orderId)->get();
    }
}
