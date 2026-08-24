<?php

use App\Actions\Checkout\HandleCheckoutCompletedAction;
use App\Enums\Order\OrderStatus;
use App\Events\Checkout\OrderPaid;
use App\Models\Event as ModelsEvent;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class, CategorySeeder::class]);
    $this->user = User::factory()->create();
    $this->event = ModelsEvent::factory()->create();
    $this->order = Order::factory()->for($this->user)->create(['event_id' => $this->event->id, 'payment_status' => OrderStatus::Pending]);
    Event::fake(OrderPaid::class);
});

function makeObject(Order $order){
    return [
        'metadata' => [
            'order_id' => (string) $order->id
        ]
    ];
};

it('dispatch the OrderPaid event with the order', function(){
    $object = makeObject($this->order);

    app(HandleCheckoutCompletedAction::class)->handle($object);

    Event::assertDispatched(OrderPaid::class, function($event){
        return $event->order->is($this->order);
    });

});

it('does not dispatch the OrderPaid event when the order payment status is paid', function(){
    $this->order->update(['payment_status' => OrderStatus::Paid]);

    $object = makeObject($this->order->fresh());

    app(HandleCheckoutCompletedAction::class)->handle($object);

    Event::assertNotDispatched(OrderPaid::class);

});

it('will fail if the order does not exist', function(){
    $object = makeObject($this->order);

    $object['metadata']['order_id'] = '99999';

    app(HandleCheckoutCompletedAction::class)->handle($object);

    Event::assertNotDispatched(OrderPaid::class); 
    
})->throws(ModelNotFoundException::class);