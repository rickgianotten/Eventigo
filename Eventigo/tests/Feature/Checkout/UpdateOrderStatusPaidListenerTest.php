<?php

use App\Actions\Checkout\UpdateOrderStatusPaidAction;
use App\Enums\Order\OrderStatus;
use App\Events\Checkout\OrderPaid;
use App\Listeners\Checkout\UpdateOrderStatusPaidListener;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class,CategorySeeder::class]);
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create();
    $this->order = Order::factory()->for($this->user)->create(['event_id' => $this->event->id, 'payment_status' => OrderStatus::Pending]);
    $this->mockedUpdateOrderStatusPaidAction = $this->mock(UpdateOrderStatusPaidAction::class);
});

it('calls the UpdateOrderStatusPaidAction with the order from the event', function(){

    $this->mockedUpdateOrderStatusPaidAction->shouldReceive('handle')->once()->withArgs(function($order){
        return $order->is($this->order);
    });

    app(UpdateOrderStatusPaidListener::class)->handle(new OrderPaid($this->order));

});

