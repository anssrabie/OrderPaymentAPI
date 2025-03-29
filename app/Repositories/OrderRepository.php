<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    public function __construct(protected Order $order)
    {
        parent::__construct($order);
    }
}
