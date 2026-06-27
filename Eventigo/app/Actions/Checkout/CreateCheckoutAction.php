<?php
namespace App\Actions\Checkout;

use App\Models\User;
use Illuminate\Support\Facades\DB;

use App\Actions\Checkout\CreateOrderAction;
use App\Models\Ticket;
use Laravel\Cashier\Checkout;

class CreateCheckoutAction{

    public function __construct(Private CreateOrderAction $createOrderAction){}

    public function handle(User $user, array $tickets): Checkout{

        $tickets = array_filter($tickets, function ($ticket) {
            return $ticket['ticket_quantity'] != 0;
        });

        [$order, $lockedTickets] = DB::transaction(function() use ($user, $tickets){
            $ticketIds = collect($tickets)->pluck('ticket_id');
            $lockedTickets = Ticket::lockForUpdate()->whereIn('id', $ticketIds)->get()->keyBy('id');

            $order = $this->createOrderAction->handle($user, $tickets, $lockedTickets);

            return [$order, $lockedTickets];
        });

        $lineItems = collect($tickets)->map(fn($ticket) =>[
            'price_data' =>[
                'currency' => 'USD',
                'unit_amount' => $lockedTickets[$ticket['ticket_id']]->price,
                'product_data' =>[
                    'name' => $lockedTickets[$ticket['ticket_id']]->type,
                ]
            ],
            'quantity' => $ticket['ticket_quantity'],
        ])->values()->toArray();

        $checkout = $user->checkout($lineItems,[
            'success_url' => route('checkout.succes'),
            'cancel_url' => route('checkout.cancel'),
            'metadata' => [
                'order_id' => $order->id,
            ]
        ]);

        $order->update(['stripe_session_id' => $checkout->id]);
        
        return $checkout;
    }
}

