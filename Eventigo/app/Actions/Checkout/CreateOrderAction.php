<?php
namespace App\Actions\Checkout;

use App\Models\Ticket;
use App\Models\User;

use App\Actions\Checkout\CreateOrderItemsAction;
use App\Enums\Order\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Collection;

/**
 * @param Ticket[] $lockedTickets
 */

class CreateOrderAction{

    public function __construct(Private CreateOrderItemsAction $CreateOrderItemsAction){}

    public function handle(User $user,array $tickets, Collection $lockedTickets): Order{

        $totalPrice = collect($tickets)->sum(fn($ticket) => $lockedTickets[$ticket['ticket_id']]->price * $ticket['ticket_quantity']);

        // order pending
        $event = $lockedTickets->first()->event;

        $order = $user->orders()->create([
            'event_id' => $event->id,
            'payment_status' => OrderStatus::Pending,
            'total_price' => $totalPrice
        ]); 

        $this->CreateOrderItemsAction->handle($order, $lockedTickets, $tickets);

        return $order;
    }
}