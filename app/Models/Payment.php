<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = ['order_id','payment_id','method','status'];

    protected function casts()
    {
        return [
            'method' => PaymentMethod::class,
            'status' => PaymentStatus::class,
        ];
    }

    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
