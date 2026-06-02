<?php

namespace App\Actions\Checkout;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CreateOrderTicketsAction{
    public function handle(Order $order){
        foreach($order->orderItems as $item){
            for($i = 0; $i < $item->quantity; $i ++){
                $item->tickets()->create([
                    'event_id' => $order->event->id,
                    'ticket_code' => Str::uuid(),
                    'valid_from' => Carbon::parse($order->event->start_date, '', $order->event->start_time),
                    'valid_until' => Carbon::parse($order->event->end_date, '', $order->event->end_time),
                ]);
            }
        }

    }
}