<?php

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Laravel\Cashier\Events\WebhookReceived;

beforeEach(function(){
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create();
    $this->order = Order::factory()->for($this->user)->create(['event_id' => $this->event_id]);
});

function makeWebhookReceivedEvent(string $type){
    return new WebhookReceived([
    'type' => $type,
    'data' => [
        'object' =>[
            'metadata' =>[
                'order_id' => $this->order->id
            ]
        ]
    ]
    ]);
}

it('calls HandleCheckoutCompletedAction when checkout session is completed', function(){})->todo();

it('calls HandleCheckoutExpiredAction when checkout session is expired', function(){})->todo();


