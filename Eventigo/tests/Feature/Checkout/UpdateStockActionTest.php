<?php

use App\Actions\Checkout\UpdateStockAction;
use App\Enums\Order\OrderStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class, CategorySeeder::class]);
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create();
    $this->ticket = Ticket::factory()->for($this->event)->create(['quantity_available' => '100', 'quantity_sold' => '0', 'price' => '200']);
    $this->order = Order::factory()->for($this->user)->create(['event_id' => $this->event->id, 'payment_status' => OrderStatus::Paid]);
    OrderItem::factory(2)->create(['order_id' => $this->order->id,'ticket_id' => $this->ticket->id, 'unit_price' => $this->ticket->price]);
});

it('increment the quantity_sold for each orderItem for a single ticket', function(){
    $totalExtraQuantity = $this->order->orderItems()->sum('quantity');

    $previousQuantitySold = $this->ticket->quantity_sold;

    app(UpdateStockAction::class)->handle($this->order);

    expect($this->ticket->fresh()->quantity_sold)->toBe($previousQuantitySold + $totalExtraQuantity);

});