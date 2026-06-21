<?php

use App\Actions\Checkout\CreateOrderTicketsAction;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([CategorySeeder::class, PricingPlanSeeder::class]);
    $this->event = Event::factory()->create();
    $this->ticket = Ticket::factory(['event_id' => $this->event->id])->create();
    $this->order = Order::factory(['event_id' => $this->event->id])->create();
    $this->orderItem = OrderItem::factory(['order_id' => $this->order->id, 'ticket_id' => $this->ticket->id, 'unit_price' => $this->ticket->price, 'quantity' => 6])->create();

});

it('create order ticket for each order item quantity', function(){
    app(CreateOrderTicketsAction::class)->handle($this->order);

    assertDatabaseCount('order_tickets', 6);

});


it('create order tickets for multiple order items', function(){
    OrderItem::factory(['order_id' => $this->order->id, 'ticket_id' => $this->ticket->id, 'unit_price' => $this->ticket->price, 'quantity' => 4])->create();

    app(CreateOrderTicketsAction::class)->handle($this->order);

    // 6 from the beforeEach hook and 4 from the local factory

    assertDatabaseCount('order_tickets', 10);

});

it('sets correct valid_from and valid_until based on event',function(){
    app(CreateOrderTicketsAction::class)->handle($this->order);

    $ticket = $this->orderItem->tickets->first();

    expect($ticket->valid_from)->toEqual($this->event->start_date)
        ->and($ticket->valid_until)->toEqual($this->event->end_date);

});

it('sets status to active', function(){
    app(CreateOrderTicketsAction::class)->handle($this->order);

    $ticket = $this->orderItem->tickets->first();

    expect($ticket->status)->toBe('active');
});

it('links order ticket to the correct event', function(){
    app(CreateOrderTicketsAction::class)->handle($this->order);

    $ticket = $this->orderItem->tickets->first();  
    
    expect($ticket->event_id)->toBe($this->event->id);
    
});

it('links order ticket to the correct order item', function(){
    app(CreateOrderTicketsAction::class)->handle($this->order);

    $ticket = $this->orderItem->tickets->first();  
    
    expect($ticket->order_item_id)->toBe($this->orderItem->id);
});