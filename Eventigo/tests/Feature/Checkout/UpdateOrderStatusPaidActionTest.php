<?php

use App\Actions\Checkout\UpdateOrderStatusPaidAction;
use App\Enums\Order\OrderStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class,CategorySeeder::class]);
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create();
    $this->order = Order::factory()->for($this->user)->create(['event_id' => $this->event->id, 'payment_status' => OrderStatus::Pending]);
});

it('can update the order status to paid and set the paid_at', function(){
    $this->freezeTime();

    app(UpdateOrderStatusPaidAction::class)->handle($this->order);

    assertDatabaseHas('orders', [
        'id' => $this->order->id,
        'payment_status' => OrderStatus::Paid,
        'paid_at' => now()
    ]);

});