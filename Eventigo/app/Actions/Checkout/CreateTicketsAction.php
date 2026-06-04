<?php

namespace App\Actions\Checkout;

use App\Models\Order;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CreateTicketsAction{
    public function handle(Order $order){
        $tickets = $order->load('orderItems.tickets')->orderItems->flatMap(fn($item) => $item->tickets);
        $tickets->map(fn($ticket) => QrCode::format('png')->generate($ticket->ticket_code));
    }
}