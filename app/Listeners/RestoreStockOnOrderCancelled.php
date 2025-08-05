<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RestoreStockOnOrderCancelled
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    
    public function handle(OrderCancelled $event): void
    {
        $order = $event->order;
        
        foreach ($order->orderItems as $item) {
            $product = $item->product;
            $product->increment('stock', $item->quantity);
        }
    }
} 