<?php

use App\Actions\Checkout\CreateOrderAction;
use App\Actions\Checkout\CreateOrderItemsAction;
use App\Enums\Order\OrderStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;
use function PHPUnit\Framework\assertNotNull;

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
    $this->mockedCreatedOrderItemsAction = Mockery::mock(CreateOrderItemsAction::class);
    app()->instance(CreateOrderItemsAction::class, $this->mockedCreatedOrderItemsAction);
});

it('can create order with status pending', function(){
    $this->mockedCreatedOrderItemsAction->shouldReceive('handle')->once()->withArgs(function($actuelOrder, $actuelLockedTickets, $actuelTickets){
        return $actuelOrder instanceof Order  && $actuelLockedTickets === $this->lockedTickets && $actuelTickets === $this->ticketsFormData;
    });

    $order = app(CreateOrderAction::class)->handle($this->user, $this->ticketsFormData, $this->lockedTickets);

    assertDatabaseHas('orders',[
        'user_id' => $this->user->id,
        'event_id' => $this->event->id,
        'payment_status' => OrderStatus::Pending,
        'total_price' => $order->total_price
    ]);

});

it('generate order number automatically', function(){
    $this->mockedCreatedOrderItemsAction->shouldReceive('handle')->once()->withArgs(function($actuelOrder, $actuelLockedTickets, $actuelTickets){
        return $actuelOrder instanceof Order  && $actuelLockedTickets === $this->lockedTickets && $actuelTickets === $this->ticketsFormData;
    });

    $order = app(CreateOrderAction::class)->handle($this->user, $this->ticketsFormData, $this->lockedTickets);

    assertNotNull($order->order_number);
});

it('calculates the correct total price', function(){
    $this->mockedCreatedOrderItemsAction->shouldReceive('handle')->once()->withArgs(function($actuelOrder, $actuelLockedTickets, $actuelTickets){
        return $actuelOrder instanceof Order  && $actuelLockedTickets === $this->lockedTickets && $actuelTickets === $this->ticketsFormData;
    });

    $totalPrice = collect($this->ticketsFormData)->sum(fn($FormData) => $this->lockedTickets[$FormData['ticket_id']]->price * $FormData['ticket_quantity']);

    $order = app(CreateOrderAction::class)->handle($this->user, $this->ticketsFormData, $this->lockedTickets);

    expect($order->total_price)->toBe($totalPrice);

});

it('returns the created order', function(){
    $this->mockedCreatedOrderItemsAction->shouldReceive('handle')->once()->withArgs(function($actuelOrder, $actuelLockedTickets, $actuelTickets){
        return $actuelOrder instanceof Order  && $actuelLockedTickets === $this->lockedTickets && $actuelTickets === $this->ticketsFormData;
    });

    $order = app(CreateOrderAction::class)->handle($this->user, $this->ticketsFormData, $this->lockedTickets);
    
    expect($order)->toBeInstanceOf(Order::class)->and($order->exists)->toBeTrue();
});
