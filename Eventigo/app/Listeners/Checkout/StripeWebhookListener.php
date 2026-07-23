<?php

namespace App\Listeners\Checkout;

use App\Actions\Checkout\HandleCheckoutCompletedAction;
use App\Actions\Checkout\HandleCheckoutExpiredAction;
use Laravel\Cashier\Events\WebhookReceived;

class StripeWebhookListener
{
    /**
     * Create the event listener.
     */
    public function __construct(private HandleCheckoutCompletedAction $checkoutCompletedAction, private HandleCheckoutExpiredAction $checkoutExpiredAction)
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
            'checkout.session.completed' => $this->checkoutCompletedAction->handle($object),
            'checkout.session.expired' => $this->checkoutExpiredAction->handle($object),
            default => null,
        };
    }
}
