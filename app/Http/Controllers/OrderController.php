<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

     
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->createOrder($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => new OrderResource($order->load('orderItems.product'))
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

     
    public function show(string $id): JsonResponse
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => new OrderResource($order)
        ]);
    }

     
    public function update(UpdateOrderRequest $request, string $id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);
            $updatedOrder = $this->orderService->updateOrderStatus($order, $request->validated()['status']);
            
            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'data' => new OrderResource($updatedOrder->load('orderItems.product'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);
            $this->orderService->cancelOrder($order);
            
            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}