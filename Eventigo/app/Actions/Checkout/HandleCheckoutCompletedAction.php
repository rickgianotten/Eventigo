<?php

namespace App\Actions\Checkout;

use App\Enums\OrderStatus;
use App\Events\Checkout\OrderPaid;
use App\Models\Order;

class HandleCheckoutCompletedAction{
    public function handle(array $object){
        $metadata = $object['metadata'];
        $order = Order::findOrFail($metadata['order_id']);

        $order->update([
            'payment_status' => OrderStatus::Paid,
            'paid_at' => now()
        ]);

        OrderPaid::dispatch($order);
    }
}