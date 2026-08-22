<?php

use App\Actions\Checkout\UpdateStockAction;
use App\Enums\Order\OrderStatus;
use App\Events\Checkout\OrderPaid;
use App\Listeners\Checkout\UpdateStockListener;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class, CategorySeeder::class]);
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create();
    $this->order = Order::factory()->for($this->user)->create(['event_id' => $this->event->id, 'payment_status' => OrderStatus::Paid]);
    $this->mockedUpdateStockAction = $this->mock(UpdateStockAction::class);
});

afterEach(function(){
    Mockery::close();
});

it('call UpdateStockAcion with the order from the event', function(){
    $this->mockedUpdateStockAction->shouldReceive('handle')->once()->withArgs(function($order){
        return $order->is($this->order);
    });

    app(UpdateStockListener::class)->handle(new OrderPaid($this->order));
});