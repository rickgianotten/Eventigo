<?php

use App\Actions\Checkout\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class, CategorySeeder::class]);
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create();
    $this->tickets = Ticket::factory(2)->create(['event_id' => $this->event->id, 'type' => 'Regular', 'quantity_available' => '20']);
    $this->ticketsFormData = $this->tickets->map(function($ticket){
        return [
            'ticket_id' => $ticket->id,
            'ticket_quantity' => '3'
        ];
    })->toArray();
    $this->lockedTickets = Ticket::with(['event'])->whereIn('id', collect($this->ticketsFormData)->pluck('ticket_id'))->get()->keyBy('id');
});

it('can create order with status pending', function(){
    $order = app(CreateOrderAction::class)->handle($this->user, $this->ticketsFormData, $this->lockedTickets);

    assertDatabaseHas('orders',[
        'user_id' => $this->user->id,
        'event_id' => $this->event->id,
        'payment_status' => OrderStatus::Pending,
        'total_price' => $order->total_price
    ]);

});

it('calculates the correct total price', function(){
    $totalPrice = collect($this->ticketsFormData)->sum(fn($FormData) => $this->lockedTickets[$FormData['ticket_id']]->price * $FormData['ticket_quantity']);

    $order = app(CreateOrderAction::class)->handle($this->user, $this->ticketsFormData, $this->lockedTickets);

    expect($order->total_price)->toBe($totalPrice);

});

it('returns the created order', function(){
    $order = app(CreateOrderAction::class)->handle($this->user, $this->ticketsFormData, $this->lockedTickets);
    
    expect($order)->toBeInstanceOf(Order::class)->and($order->exists)->toBeTrue();
});

it('links order to the correct event', function(){
    $order = app(CreateOrderAction::class)->handle($this->user, $this->ticketsFormData, $this->lockedTickets);

    expect($order->id)->toBe($this->event->id);
});

it('links order to user', function(){
    $order = app(CreateOrderAction::class)->handle($this->user, $this->ticketsFormData, $this->lockedTickets);

    expect($order->user_id)->toBe($this->user->id);    
});