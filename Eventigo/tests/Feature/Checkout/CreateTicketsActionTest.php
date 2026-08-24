<?php

use App\Actions\Checkout\CreateOrderTicketsAction;
use App\Actions\Checkout\CreateTicketsAction;
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
    $this->order = Order::factory()->for($this->user)->create(['event_id' => $this->event->id, 'payment_status' => OrderStatus::Paid]);
});

it('generate for each ticket a qr-code when a order have one order item ', function(){
    $ticket = Ticket::factory()->for($this->event)->create(['quantity_available' => '100', 'quantity_sold' => '0']);
    OrderItem::factory()->create(['order_id' => $this->order->id, 'ticket_id' => $ticket->id, 'unit_price' => $ticket->price]);

    app(CreateOrderTicketsAction::class)->handle($this->order->fresh());

    $qrCodes = app(CreateTicketsAction::class)->handle($this->order->fresh());

    expect($qrCodes)
    ->toHaveCount($this->order->fresh()->orderItems->first()->quantity)
    ->toBeArray();

    foreach($qrCodes as $qrCode){
        expect($qrCode)->toHaveKeys(['ticket_code', 'svg']);
        expect((string) $qrCode['svg'])->toContain('<svg');
        
    }

});

it('each qr-code get the right ticket_code when a order have one order item', function(){
    $ticket = Ticket::factory()->for($this->event)->create(['quantity_available' => '100', 'quantity_sold' => '0']);
    OrderItem::factory()->create(['order_id' => $this->order->id, 'ticket_id' => $ticket->id, 'unit_price' => $ticket->price]);
    
    app(CreateOrderTicketsAction::class)->handle($this->order->fresh());

    $qrCodes = app(CreateTicketsAction::class)->handle($this->order->fresh());
   
    $ticketCodes = $this->order->fresh()->orderItems->first()->tickets->map(function($ticket){
        return $ticket['ticket_code'];
    })->toArray();

    foreach($qrCodes as $qrCode){
        expect($qrCode['ticket_code'])->toBeIn($ticketCodes);
    }
   
});

it('generate for each ticket a qr-code when a order have multiple order items with different tickets', function(){
    $tickets = Ticket::factory(2)->for($this->event)->create(['quantity_available' => '100', 'quantity_sold' => '0']);
    $tickets->each(function ($ticket) {
        OrderItem::factory()->create(['order_id' => $this->order->id, 'ticket_id' => $ticket->id, 'unit_price' => $ticket->price]);
    });

    app(CreateOrderTicketsAction::class)->handle($this->order->fresh());

    $qrCodes = app(CreateTicketsAction::class)->handle($this->order->fresh());

    expect($qrCodes)
    ->toHaveCount($this->order->fresh()->orderItems->sum('quantity'))
    ->toBeArray();

    foreach($qrCodes as $qrCode){
        expect($qrCode)->toHaveKeys(['ticket_code', 'svg']);
        expect((string) $qrCode['svg'])->toContain('<svg'); 
    }

});

it('each qr-code get the right ticket_code when a order have multiple order items with different tickets', function(){
    $tickets = Ticket::factory(2)->for($this->event)->create(['quantity_available' => '100', 'quantity_sold' => '0']);
    $tickets->each(function ($ticket) {
        OrderItem::factory()->create(['order_id' => $this->order->id, 'ticket_id' => $ticket->id, 'unit_price' => $ticket->price]);
    });
 
    app(CreateOrderTicketsAction::class)->handle($this->order->fresh());

    $qrCodes = app(CreateTicketsAction::class)->handle($this->order->fresh());
    
    $orderTickets = $this->order->fresh()->orderItems->map(fn($item) => $item->tickets);

    $ticketCodes = $orderTickets->map(function($tickets){
        return $tickets->map(fn($ticket) => $ticket['ticket_code']);
    })->toArray();

    foreach($qrCodes as $qrCode){
        expect($qrCode['ticket_code'])->toBeIn(array_merge(...$ticketCodes));
    }

});