<?php

namespace App\Listeners\Checkout;

use App\Actions\Checkout\CreateTicketsAction;
use App\Events\Checkout\OrderCreated;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;

class OrderCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct(private CreateTicketsAction $action)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $user = $event->order->user;

        $qrCodes = $this->action->handle($event->order);

        Mail::to($user)->send(new OrderConfirmationMail($event->order, $qrCodes));
    }
}
