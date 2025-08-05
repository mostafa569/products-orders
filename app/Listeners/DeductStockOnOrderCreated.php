<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeductStockOnOrderCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

     
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        
        foreach ($order->orderItems as $item) {
            $product = $item->product;
            $product->decrement('stock', $item->quantity);
        }
    }
} 