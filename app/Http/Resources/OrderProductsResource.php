<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $quantity = $this->pivot?->quantity;
        $unitPrice = $this->pivot?->unit_price;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'quantity' => $quantity,
            'unit_price' => (float) $this->pivot?->unit_price,
            'total_price' => $quantity * $unitPrice
        ];
    }
}
