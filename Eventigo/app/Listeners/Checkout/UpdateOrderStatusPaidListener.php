<?php

namespace App\Listeners\Checkout;

use App\Actions\Checkout\UpdateOrderStatusPaidAction;
use App\Events\Checkout\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class UpdateOrderStatusPaidListener
{
    /**
     * Create the event listener.
     */
    public function __construct( private UpdateOrderStatusPaidAction $action)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPaid $event): void
    {
        $this->action->handle($event->order);
    }
}
