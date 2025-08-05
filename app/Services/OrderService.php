<?php

namespace App\Services;

use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
     
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            
            $this->validateStockAvailability($data['items']);
            
             
            $order = Order::create([
                'customer_name' => $data['customer_name'],
                'status' => 'pending'
            ]);
            
         
            foreach ($data['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $product->price
                ]);
            }
            
             
            event(new OrderCreated($order));
            
            return $order->load('orderItems.product');
        });
    }
    
    
    private function validateStockAvailability(array $items): void
    {
        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);
            
            if ($product->stock < $item['quantity']) {
                throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$product->stock}, Requested: {$item['quantity']}");
            }
        }
    }
    
    
    public function updateOrderStatus(Order $order, string $status): Order
    {
        $oldStatus = $order->status;
        $order->update(['status' => $status]);
        
         if ($status === 'cancelled' && $oldStatus !== 'cancelled') {
            event(new \App\Events\OrderCancelled($order));
        }
        
        return $order;
    }
    
   
    public function cancelOrder(Order $order): Order
    {
        if ($order->status === 'completed') {
            throw new \Exception('Cannot cancel a completed order');
        }
        
        $order->update(['status' => 'cancelled']);
        event(new \App\Events\OrderCancelled($order));
        
        return $order;
    }
} 