<?php
namespace App\Actions\Checkout;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Collection;

/**
 * @param Ticket[] $lockedTickets
 */

class CreateOrderItemsAction{
    public function handle(Order $order, Collection $lockedTickets, array $tickets ){

        collect($tickets)->each(function($ticket) use ($order, $lockedTickets){
            $lockedTicket = $lockedTickets[$ticket['ticket_id']];

            $order->orderItems()->create([
                'ticket_id' => $lockedTicket->id,
                'quantity' => $ticket['ticket_quantity'],
                'unit_price' => $lockedTicket->price
            ]);
        });
    }
}