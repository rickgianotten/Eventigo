<?php

namespace App\Actions\Checkout;

use App\Enums\Order\OrderStatus;
use App\Models\Order;

class UpdateOrderStatusPaidAction{
    public function handle(Order $order){
        $order->update([
            'payment_status' => OrderStatus::Paid,
            'paid_at' => now()
        ]);
    }
}