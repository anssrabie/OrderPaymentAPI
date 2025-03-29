<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Repositories\OrderRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderService extends BaseService
{
    public function __construct(protected OrderRepository $orderRepository,protected ProductService $productService)
    {
        parent::__construct($orderRepository);
    }

    public function storeOrder(array $products, int $userId)
    {
        return DB::transaction(function () use ($products,$userId) {

            $productModels = $this->productService->getProductsByIds(array_column($products, 'id'));
            [$totalPrice, $orderProducts] = $this->prepareOrderData($products, $productModels);

            $order = $this->storeResource([
                'user_id' => $userId,
                'total_price' => $totalPrice
            ]);

            $order->products()->sync($orderProducts);

            $order->refresh();

            return $order;
        });
    }

    public function updateOrder(int $orderId, array $products)
    {
        return DB::transaction(function () use ($orderId, $products) {
            // Find the order
            $order = $this->showResource($orderId, ['products', 'payments']);

            // Check if the order has any payments
            if ($order->payments()->exists()) {
                throw new \Exception("This order cannot be updated because it has associated payments.", 422);
            }

            // Fetch product details
            $productModels = $this->productService->getProductsByIds(array_column($products, 'id'));
            [$totalPrice, $orderProducts] = $this->prepareOrderData($products, $productModels);

            // Update order details
            $order->update([
                'total_price' => $totalPrice,
            ]);

            // Sync products with new quantities & prices
            $order->products()->sync($orderProducts);

            // Refresh the order to get the latest data
            $order->refresh();

            return $order;
        });
    }

    public function deleteOrder(int $orderId)
    {
        // Find the order with related payments
        $order = $this->showResource($orderId, ['payments']);

        // Check if the order has any payments
        if ($order->payments()->exists()) {
            throw new \Exception("This order cannot be deleted because it has associated payments.", 422);
        }

        // Proceed with deletion
        return $order->delete();
    }

    public function changeOrderStatus(int $orderId,string $status){

        // Find the order
        $order = $this->showResource($orderId);

        // Check if the order status not pending
        if ($order->status->value !== OrderStatus::Pending->value) {
            throw new \Exception("Order status cannot be changed. Current status: {$order->status->name}. Only pending orders can be updated.", 400);
        }

        // Update Order status
        $order->update(['status' => $status]);

        return $order;
    }


    private function prepareOrderData(array $products, Collection $productModels): array
    {
        $totalPrice = 0;
        $orderProducts = [];

        foreach ($products as $product) {
            $productData = $productModels->firstWhere('id', $product['id']);
            $price = $productData->price;
            $quantity = $product['quantity'] ?? 1;

            $totalPrice += $price * $quantity;

            $orderProducts[$product['id']] = [
                'quantity' => $quantity,
                'unit_price' => $price
            ];
        }
        return [$totalPrice, $orderProducts];
    }
}
