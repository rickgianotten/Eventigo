<?php

use App\Actions\Checkout\HandleCheckoutCompletedAction;
use App\Actions\Checkout\HandleCheckoutExpiredAction;
use App\Listeners\Checkout\StripeWebhookListener;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Events\WebhookReceived;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class,CategorySeeder::class]);
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create();
    $this->order = Order::factory()->for($this->user)->create(['event_id' => $this->event->id]);
    $this->mockedHandleCheckoutCompletedAction = $this->mock(HandleCheckoutCompletedAction::class);
    $this->mockedHandleCheckoutExpiredAction = $this->mock(HandleCheckoutExpiredAction::class);
});

afterEach(function(){
    Mockery::close();
});

function makeWebhookReceivedEvent(string $type, Order $order){
    return new WebhookReceived([
    'type' => $type,
    'data' => [
        'object' =>[
            'metadata' =>[
                'order_id' => $order->id
            ]
        ]
    ]
    ]);
}

it('calls HandleCheckoutCompletedAction when checkout session is completed', function(){
    $webhookReceivedEvent = makeWebhookReceivedEvent('checkout.session.completed', $this->order);

    $this->mockedHandleCheckoutCompletedAction->shouldReceive('handle')->once()->withArgs(function($object) use($webhookReceivedEvent){
        return $object == $webhookReceivedEvent->payload['data']['object'];
    });

    $this->mockedHandleCheckoutExpiredAction->shouldNotReceive('handle');

    app(StripeWebhookListener::class)->handle($webhookReceivedEvent);

});

it('calls HandleCheckoutExpiredAction when checkout session is expired', function(){
    $webhookReceivedEvent = makeWebhookReceivedEvent('checkout.session.expired', $this->order);

    $this->mockedHandleCheckoutExpiredAction->shouldReceive('handle')->once()->withArgs(function($object) use($webhookReceivedEvent){
        return $object == $webhookReceivedEvent->payload['data']['object'];
    });

    $this->mockedHandleCheckoutCompletedAction->shouldNotReceive('handle');

    app(StripeWebhookListener::class)->handle($webhookReceivedEvent);

});


