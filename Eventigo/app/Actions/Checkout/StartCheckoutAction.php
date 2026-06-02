<?php
namespace App\Actions\Checkout;

use App\Models\User;
use Illuminate\Support\Facades\DB;

use App\Actions\Checkout\CreateOrderAction;
use App\Models\Ticket;

class StartCheckoutAction{

    public function __construct(Private CreateOrderAction $createOrderAction){}

    public function handle(User $user, array $tickets){
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
            'quantity' => $ticket['quantity'],
        ])->values()->toArray();

        // stripe checkout
        $checkout = $user->checkout($lineItems,[
            'succes_url' => '',
            'cancel_url' => '',
            'customer_email' => $user->email,
            'metadata' => [
                'order_id' => $order->id,
            ]
        ]);

        $order->update(['stripe_session_id' => $checkout->id]);
        
        
        // update order paid in webhook
        // order tickets in webhook
        //mail in webhook
    }
}


//$tickets = [
//  [
//    ticket_id => '',
//    ticket_quanity => '',
//  ]
//]