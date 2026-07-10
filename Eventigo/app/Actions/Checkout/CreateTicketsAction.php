<?php

namespace App\Actions\Checkout;

use App\Models\Order;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CreateTicketsAction{
    public function handle(Order $order):array{
        $tickets = $order->orderItems->flatMap(fn($item) => $item->tickets);
        return $tickets->map(fn($ticket) => [
            'ticket_code' => $ticket->ticket_code,
            'svg' => QrCode::format('svg')->generate($ticket->ticket_code),
        ])->toArray();
    }
}