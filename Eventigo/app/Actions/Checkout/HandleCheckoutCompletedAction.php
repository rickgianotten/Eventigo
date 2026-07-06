<?php

namespace App\Actions\Checkout;

use App\Enums\OrderStatus;
use App\Events\Checkout\OrderPaid;
use App\Models\Order;

class HandleCheckoutCompletedAction{
    public function handle(array $object){
        $metadata = $object['metadata'];
        $order = Order::findOrFail($metadata['order_id']);

        if($order->payment_status === OrderStatus::Paid){
            return;
        }

        OrderPaid::dispatch($order);
    }
}