<?php

namespace App\Actions\Checkout;

use App\Enums\Order\OrderStatus;
use App\Models\Order;

class HandleCheckoutExpiredAction{
    public function handle(array $object){
        $metadata = $object['metadata'];
        $order = Order::findOrFail($metadata['order_id']);

        $order->update(['payment_status' => OrderStatus::Cancelled]);
    }
}