<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderRequest;
use App\Http\Requests\Api\OrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrdersResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $orders = $this->orderService->getData(usePagination: true,filters: ['status']);
        return $this->returnData(OrdersResource::collection($orders)->response()->getData(),'All orders');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request):JsonResponse
    {
        try {
            $order = $this->orderService->storeOrder($request->products,auth()->id());
            return $this->returnData(new OrderResource($order), 'Order has been created successfully',201);
        } catch (\Exception $exception) {
            return $this->errorMessage(__($exception->getMessage()), $exception->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id):JsonResponse
    {
        try {
            $order = $this->orderService->showResource($id,['products']);
            return $this->returnData(new OrderResource($order), 'Show Order');
        }
        catch (\Exception $exception) {
            return $this->errorMessage(__($exception->getMessage()), $exception->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, string $id):JsonResponse
    {
        try {
            $order = $this->orderService->updateOrder($id,$request->products);;
            return $this->returnData(new OrderResource($order), 'Order has been updated successfully');
        } catch (\Exception $exception) {
            return $this->errorMessage($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id):JsonResponse
    {
        try {
            $this->orderService->deleteOrder($id);
            return $this->successMessage( 'Order has been deleted successfully');
        } catch (\Exception $exception) {
            return $this->errorMessage($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Change order status by id.
     */
    public function changeStatus(OrderStatusRequest $request,string $id){
        try {
            $order = $this->orderService->changeOrderStatus($id,$request->input('status'));
            return $this->returnData(new OrderResource($order),'Order status has been changed successfully');
        } catch (\Exception $exception) {
            return $this->errorMessage($exception->getMessage(), $exception->getCode());
        }
    }


}
