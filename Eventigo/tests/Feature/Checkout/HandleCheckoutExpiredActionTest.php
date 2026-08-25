<?php

use App\Actions\Checkout\HandleCheckoutExpiredAction;
use App\Enums\Order\OrderStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class, CategorySeeder::class]);
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create();
    $this->order = Order::factory()->for($this->user)->create(['event_id' => $this->event->id, 'payment_status' => OrderStatus::Pending]);
});

function makeObjectCheckoutExpired(Order $order){
    return [
        'metadata' => [
            'order_id' => (string) $order->id
        ]
    ];
};

it('will update the order payment status to cancelled', function(){
    $object = makeObjectCheckoutExpired($this->order);

    app(HandleCheckoutExpiredAction::class)->handle($object);

    expect($this->order->fresh()->payment_status)->toBe(OrderStatus::Cancelled);
});

it('will throw an model not found exception if the order does not exist', function(){
    $object = makeObjectCheckoutExpired($this->order);
    $object['metadata']['order_id'] = '9999'; 

    app(HandleCheckoutExpiredAction::class)->handle($object);

})->throws(ModelNotFoundException::class);