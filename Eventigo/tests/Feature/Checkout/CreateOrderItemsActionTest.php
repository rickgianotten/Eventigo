<?php

use App\Actions\Checkout\CreateOrderItemsAction;
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
    $this->event = Event::factory()->create();
    $this->user = User::factory()->create();
    $this->order = Order::factory()->create([
        'event_id' => $this->event->id,
        'user_id' => $this->user->id,
        'payment_status' => OrderStatus::Pending,
        'paid_at' => null,
    ]);
    $this->tickets = Ticket::factory(2)->create([
        'event_id' => $this->event->id, 
        'type' => 'Regular', 
        'quantity_available' => '20'
    ]);
    $this->ticketsFormData = $this->tickets->map(function($ticket){
        return [
            'ticket_id' => $ticket->id,
            'ticket_quantity' => '3'
        ];
    })->toArray();
    $this->lockedTickets = Ticket::with(['event'])->whereIn('id', collect($this->ticketsFormData)->pluck('ticket_id'))->get()->keyBy('id');
});


it('can store order items for multipule tickets', function(){
    app(CreateOrderItemsAction::class)->handle($this->order, $this->lockedTickets, $this->ticketsFormData);

   collect($this->ticketsFormData)->each(function ($formData) {
        $lockedTicket = $this->lockedTickets[$formData['ticket_id']];

        assertDatabaseHas('order_items',[
            'order_id' => $this->order->id,
            'ticket_id' => $lockedTicket->id,
            'quantity' => $formData['ticket_quantity'],
            'unit_price' => $lockedTicket->price
        ]);
   });
});

it('can store order items for free ticket', function(){
    $freeTickets = Ticket::factory(2)->create(['event_id' => $this->event->id, 'type' => 'Free', 'price' => null]);

    $ticketsFormData = $freeTickets->map(function($ticket){
        return [
            'ticket_id' => $ticket->id,
            'ticket_quantity' => '3'
        ];
    })->toArray();

    $lockedTickets = Ticket::with(['event'])->whereIn('id', collect($ticketsFormData)->pluck('ticket_id'))->get()->keyBy('id');

    app(CreateOrderItemsAction::class)->handle($this->order, $lockedTickets, $ticketsFormData);

    collect($ticketsFormData)->each(function ($formData) use ($lockedTickets) {
            $lockedTicket = $lockedTickets[$formData['ticket_id']];

            assertDatabaseHas('order_items',[
                'order_id' => $this->order->id,
                'ticket_id' => $lockedTicket->id,
                'quantity' => $formData['ticket_quantity'],
                'unit_price' => $lockedTicket->price
            ]);
    });
    
});

it('can store order items for free ticket and priced ticket', function(){
    $tickets = Ticket::factory()->count(2)->sequence(
        ['event_id' => $this->event->id, 'type' => 'Free', 'price' => null],
        ['event_id' => $this->event->id, 'type' => 'Regular', 'quantity_available' => '20']
    )->create();

    $ticketsFormData = $tickets->map(function($ticket){
        return [
            'ticket_id' => $ticket->id,
            'ticket_quantity' => '2'
        ];
    })->toArray();

    $lockedTickets = Ticket::with(['event'])->whereIn('id', collect($ticketsFormData)->pluck('ticket_id'))->get()->keyBy('id');

    app(CreateOrderItemsAction::class)->handle($this->order, $lockedTickets, $ticketsFormData);

    collect($ticketsFormData)->each(function ($formData) use ($lockedTickets){
            $lockedTicket = $lockedTickets[$formData['ticket_id']];

            assertDatabaseHas('order_items',[
                'order_id' => $this->order->id,
                'ticket_id' => $lockedTicket->id,
                'quantity' => $formData['ticket_quantity'],
                'unit_price' => $lockedTicket->price
            ]);
    });

});
