<?php

namespace App\Listeners\Checkout;

use App\Actions\Checkout\UpdateStockAction;
use App\Events\Checkout\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateStockListener
{
    /**
     * Create the event listener.
     */
    public function __construct(private UpdateStockAction $action)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPaid $event): void
    {
        // update stock action aanroepen
        $this->action->handle($event->order);
    }
}
