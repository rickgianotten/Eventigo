<?php
namespace App\Actions\Checkout;

use App\Models\User;
use Illuminate\Support\Facades\DB;

use App\Actions\Checkout\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Events\Checkout\OrderPaid;
use App\Models\Order;
use App\Models\Ticket;
use Error;
use Exception;
use Laravel\Cashier\Checkout;

class CreateCheckoutAction{

    public function __construct(Private CreateOrderAction $createOrderAction){}

    public function handle(User $user, array $tickets): Checkout | Order{

        $tickets = array_filter($tickets, function ($ticket) {
            return $ticket['ticket_quantity'] != 0;
        });

        [$order, $lockedTickets] = DB::transaction(function() use ($user, $tickets){
            $ticketIds = collect($tickets)->pluck('ticket_id');
            $lockedTickets = Ticket::lockForUpdate()->whereIn('id', $ticketIds)->get()->keyBy('id');
            
            foreach($tickets as $ticket){
                $lockedTicket = $lockedTickets[$ticket['ticket_id']];
                if($ticket['ticket_quantity'] > $lockedTicket->available()){
                    throw new Exception('Not enough tickets available!');
                }
            };

            $order = $this->createOrderAction->handle($user, $tickets, $lockedTickets);

            return [$order, $lockedTickets];
        });

        $isFree = collect($tickets)->every(
            fn($ticket) => is_null($lockedTickets[$ticket['ticket_id']]->price)
        );

        if($isFree){
            OrderPaid::dispatch($order);
            return $order; 
        };

        $lineItems = collect($tickets)
        ->filter(fn($ticket)=> !is_null($lockedTickets[$ticket['ticket_id']]->price))
        ->map(fn($ticket) =>[
            'price_data' =>[
                'currency' => 'USD',
                'unit_amount' => $lockedTickets[$ticket['ticket_id']]->price,
                'product_data' =>[
                    'name' => $lockedTickets[$ticket['ticket_id']]->type,
                ]
            ],
            'quantity' => $ticket['ticket_quantity'],
        ])->values()->toArray();

        try{
            session(['checkout_completed' => true]);
            $checkout = $user->checkout($lineItems,[
                'success_url' => route('checkout.succes') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
                'metadata' => [
                    'order_id' => $order->id,
                ]
            ]);
        }catch(Exception $e){
            session()->forget('checkout_completed');
            $order->update(['payment_status' => OrderStatus::Failed]);
            throw new Exception('Oops, something went wrong at checkout. Please try again.');
        };


        $order->update(['stripe_session_id' => $checkout->id]);
        
        return $checkout;
    }
}

