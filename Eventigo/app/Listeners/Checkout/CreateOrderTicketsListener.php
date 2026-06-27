<?php

namespace App\Listeners\Checkout;

use App\Actions\Checkout\CreateOrderTicketsAction;
use App\Events\Checkout\OrderCreated;
use App\Events\Checkout\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateOrderTicketsListener
{
    /**
     * Create the event listener.
     */
    public function __construct(private CreateOrderTicketsAction $action)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPaid $event): void
    {
        // create order tickets action aanroepen
        $this->action->handle($event->order);

        // event OrderCreated afvuren
        OrderCreated::dispatch($event->order);
    }
}
