<?php

use App\Actions\Checkout\CreateOrderTicketsAction;
use App\Enums\Order\OrderStatus;
use App\Events\Checkout\OrderCreated;
use App\Events\Checkout\OrderPaid;
use App\Listeners\Checkout\CreateOrderTicketsListener;
use App\Models\Event as ModelsEvent;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class, CategorySeeder::class]);
    $this->user = User::factory()->create();
    $this->event = ModelsEvent::factory()->create();
    $this->order = Order::factory()->for($this->user)->create(['event_id' => $this->event->id, 'payment_status' => OrderStatus::Paid]);
    $this->mockedCreateOrderTicketsAction = $this->mock(CreateOrderTicketsAction::class);
    Event::fake(OrderCreated::class);
});

it('calls CreateOrderTicketsAction with the order from the event',function(){
    $this->mockedCreateOrderTicketsAction->shouldReceive('handle')->once()->withArgs(function($order){
        return $order->is($this->order);
    });

    app(CreateOrderTicketsListener::class)->handle(new OrderPaid($this->order));
    
});

it('dispatch the OrderCreated event with the order in the event',function(){

    $this->mockedCreateOrderTicketsAction->shouldReceive('handle')->once();

    app(CreateOrderTicketsListener::class)->handle(new OrderPaid($this->order));

    Event::assertDispatched(OrderCreated::class, function($event){
        return $event->order->is($this->order);
    });

});