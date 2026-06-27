<?php

namespace App\Actions\Checkout;

use App\Models\Order;

class UpdateStockAction{
    public function handle(Order $order){
        foreach($order->orderItems as $item){
            $ticket = $item->ticket;
            $ticket->increment('quantity_sold', $item->quantity);
        }
    }
}