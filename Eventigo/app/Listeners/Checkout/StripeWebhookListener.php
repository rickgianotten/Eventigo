<?php

namespace App\Listeners\Checkout;

use App\Actions\Checkout\HandleCheckoutCompletedAction;
use App\Events\Checkout\OrderPaid;
use Laravel\Cashier\Events\WebhookReceived;

class StripeWebhookListener
{
    /**
     * Create the event listener.
     */
    public function __construct(private HandleCheckoutCompletedAction $action)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        $type = $event->payload['type'];
        $object = $event->payload['data']['object'];

        match ($type) {
            'checkout.session.completed' => $this->action->handle($object),
            default => null,
        };
    }
}
